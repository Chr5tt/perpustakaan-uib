<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        // Auto-update status overdue + denda berjalan
        Loan::refreshOverdue();

        $query = Loan::with(['book', 'user']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('loan_code', 'like', "%$s%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$s%"))
                  ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%$s%"));
            });
        }

        $loans = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'active'   => Loan::where('status', 'active')->count(),
            'overdue'  => Loan::where('status', 'overdue')->count(),
            'returned' => Loan::where('status', 'returned')->count(),
            'pending'  => Loan::where('status', 'pending')->count(),
        ];

        return view('loans.index', compact('loans', 'counts'));
    }

    public function create(Request $request)
    {
        $members = User::whereIn('role', ['mahasiswa', 'dosen', 'staff'])->orderBy('name')->get();
        $books = Book::with('category')->orderBy('title')->get();
        return view('loans.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'book_id'   => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date'  => 'required|date|after_or_equal:loan_date',
            'notes'     => 'nullable|string',
        ]);

        $existing = Loan::where('user_id', $data['user_id'])
            ->where('book_id', $data['book_id'])
            ->whereIn('status', ['active', 'overdue', 'pending'])
            ->first();

        if ($existing) {
            return back()->withErrors(['book_id' => 'Anggota ini sudah meminjam / mengajukan buku tersebut.'])->withInput();
        }

        $loan = DB::transaction(function () use ($data) {
            $book = Book::lockForUpdate()->findOrFail($data['book_id']);

            if ($book->available <= 0) {
                return null;
            }

            $loan = Loan::create([
                ...$data,
                'loan_code'    => $this->generateLoanCode(),
                'processed_by' => auth()->id(),
                'status'       => 'active',
            ]);

            $book->decrement('available');

            // Reservasi aktif peminjam untuk buku ini dianggap selesai
            $book->reservations()
                 ->where('user_id', $data['user_id'])
                 ->whereIn('status', ['waiting', 'ready'])
                 ->update(['status' => 'completed']);

            return $loan;
        });

        if (!$loan) {
            return back()->withErrors(['book_id' => 'Buku tidak tersedia untuk dipinjam.'])->withInput();
        }

        return redirect()->route('loans.show', $loan)->with('success', 'Peminjaman berhasil dicatat!');
    }

    /**
     * Member (mahasiswa/dosen/staff) mengajukan peminjaman -> status pending.
     * Stok baru berkurang saat admin/staff menyetujui.
     */
    public function requestLoan(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($data['book_id']);

        if ($book->available <= 0) {
            return back()->with('error', 'Buku sedang tidak tersedia. Silakan buat reservasi.');
        }

        $existing = Loan::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereIn('status', ['active', 'overdue', 'pending'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah meminjam atau mengajukan buku ini.');
        }

        Loan::create([
            'loan_code' => $this->generateLoanCode(),
            'user_id'   => auth()->id(),
            'book_id'   => $book->id,
            'loan_date' => now()->toDateString(),
            'due_date'  => now()->addDays(14)->toDateString(),
            'status'    => 'pending',
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Pengajuan peminjaman terkirim. Menunggu persetujuan petugas perpustakaan.');
    }

    public function show(Loan $loan)
    {
        // Non-admin hanya boleh melihat peminjaman miliknya sendiri
        if (!auth()->user()->isAdmin() && (int) $loan->user_id !== (int) auth()->id()) {
            abort(403, 'Anda tidak berhak melihat data peminjaman ini.');
        }

        if (in_array($loan->status, ['active', 'overdue'])) {
            Loan::refreshOverdue();
            $loan->refresh();
        }

        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $members = User::whereIn('role', ['mahasiswa', 'dosen', 'staff'])->orderBy('name')->get();
        $books = Book::with('category')->orderBy('title')->get();
        return view('loans.edit', compact('loan', 'members', 'books'));
    }

    public function update(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'due_date' => 'required|date|after_or_equal:' . $loan->loan_date->toDateString(),
            'notes'    => 'nullable|string',
        ]);

        $loan->update($data);

        if (in_array($loan->status, ['active', 'overdue'])) {
            // Jatuh tempo diperpanjang/dipersingkat -> status & denda ikut disesuaikan
            $loan->refresh();
            if ($loan->due_date->gte(now()->startOfDay())) {
                $loan->update(['status' => 'active', 'fine' => 0]);
            }
            Loan::refreshOverdue();
        }
        return redirect()->route('loans.show', $loan)->with('success', 'Peminjaman berhasil diperbarui!');
    }

    public function destroy(Loan $loan)
    {
        if (in_array($loan->status, ['active', 'overdue'])) {
            return back()->with('error', 'Tidak bisa menghapus peminjaman yang masih aktif!');
        }
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Data peminjaman dihapus.');
    }

    public function returnBook(Loan $loan)
    {
        if (!in_array($loan->status, ['active', 'overdue'])) {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        $fine = 0;

        DB::transaction(function () use ($loan, &$fine) {
            $loan->return_date = now()->toDateString();
            $loan->status      = 'returned';
            $fine              = $loan->calculateFine();

            $loan->update([
                'status'       => 'returned',
                'return_date'  => now()->toDateString(),
                'fine'         => $fine,
                'processed_by' => $loan->processed_by ?? auth()->id(),
            ]);

            $book = $loan->book;
            // Jangan sampai available melebihi stock
            if ($book->available < $book->stock) {
                $book->increment('available');
            }

            // Reservasi berikutnya jadi "ready" (siap diambil)
            $nextRes = $book->reservations()
                            ->where('status', 'waiting')
                            ->oldest()
                            ->first();
            if ($nextRes) {
                $nextRes->update([
                    'status'      => 'ready',
                    'expiry_date' => now()->addDays(3)->toDateString(),
                ]);
            }
        });

        $msg = 'Buku berhasil dikembalikan!';
        if ($fine > 0) {
            $msg .= ' Denda: Rp ' . number_format($fine, 0, ',', '.');
        }

        return back()->with('success', $msg);
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini tidak dalam status pending.');
        }

        $ok = DB::transaction(function () use ($loan) {
            $book = Book::lockForUpdate()->findOrFail($loan->book_id);

            if ($book->available <= 0) {
                return false;
            }

            // Hitung ulang periode pinjam mulai hari persetujuan (lama pinjam tetap)
            $days = max(1, $loan->loan_date->diffInDays($loan->due_date));

            $loan->update([
                'status'       => 'active',
                'loan_date'    => now()->toDateString(),
                'due_date'     => now()->addDays((int) $days)->toDateString(),
                'processed_by' => auth()->id(),
            ]);

            $book->decrement('available');

            $book->reservations()
                 ->where('user_id', $loan->user_id)
                 ->whereIn('status', ['waiting', 'ready'])
                 ->update(['status' => 'completed']);

            return true;
        });

        if (!$ok) {
            return back()->with('error', 'Stok buku habis, peminjaman belum bisa disetujui.');
        }

        return back()->with('success', 'Peminjaman disetujui!');
    }

    private function generateLoanCode(): string
    {
        do {
            $code = 'LN-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Loan::where('loan_code', $code)->exists());

        return $code;
    }
}
