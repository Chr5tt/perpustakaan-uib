<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        // Reservasi "siap diambil" yang melewati batas waktu otomatis dibatalkan
        Reservation::where('status', 'ready')
            ->whereDate('expiry_date', '<', now()->toDateString())
            ->update(['status' => 'cancelled']);

        $query = Reservation::with(['book', 'user'])->latest();

        // Non-admin hanya lihat reservasi sendiri
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $reservations = $query->paginate(15);
        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Admin/staff mengelola sirkulasi langsung; reservasi untuk anggota
        if (auth()->user()->isAdmin()) {
            return back()->with('error', 'Admin/Staff tidak perlu membuat reservasi. Gunakan menu Peminjaman.');
        }

        $existing = Reservation::where('user_id', auth()->id())
            ->where('book_id', $data['book_id'])
            ->whereIn('status', ['waiting', 'ready'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah punya reservasi aktif untuk buku ini.');
        }

        Reservation::create([
            'user_id'          => auth()->id(),
            'book_id'          => $data['book_id'],
            'reservation_date' => now()->toDateString(),
            'expiry_date'      => now()->addDays(7)->toDateString(),
            'status'           => 'waiting',
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dibuat!');
    }

    public function destroy(Reservation $reservation)
    {
        if ((int) $reservation->user_id !== (int) auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $reservation->update(['status' => 'cancelled']);
        return back()->with('success', 'Reservasi dibatalkan.');
    }
}
