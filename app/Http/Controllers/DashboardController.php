<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Pastikan status terlambat & denda selalu terbaru
        Loan::refreshOverdue();

        $stats = [
            'total_books'          => Book::count(),
            'available_books'      => Book::where('available', '>', 0)->count(),
            'active_loans'         => Loan::where('status', 'active')->count(),
            'overdue_loans'        => Loan::where('status', 'overdue')->count(),
            'total_members'        => User::whereIn('role', ['mahasiswa', 'dosen', 'staff'])->count(),
            'pending_reservations' => Reservation::where('status', 'waiting')->count(),
            'total_fines'          => Loan::sum('fine'),
            // Personal stats for non-admin
            'my_active_loans'      => $user->loans()->whereIn('status', ['active', 'overdue'])->count(),
            'my_reservations'      => $user->reservations()->whereIn('status', ['waiting', 'ready'])->count(),
        ];

        $recentLoans = $user->isAdmin()
            ? Loan::with(['book', 'user'])->latest()->take(8)->get()
            : $user->loans()->with('book')->latest()->take(8)->get();

        $popularBooks = Book::withCount('loans')
            ->orderByDesc('loans_count')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentLoans', 'popularBooks'));
    }
}
