<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    /** Denda per hari keterlambatan (Rupiah) */
    public const FINE_PER_DAY = 1000;

    protected $fillable = [
        'loan_code', 'user_id', 'book_id', 'processed_by',
        'loan_date', 'due_date', 'return_date', 'status', 'fine', 'notes'
    ];

    protected $casts = [
        'loan_date'   => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
        'fine'        => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Hitung denda: Rp 1.000 per hari lewat jatuh tempo.
     * Jika sudah dikembalikan, dihitung sampai tanggal kembali; jika belum, sampai hari ini.
     */
    public function calculateFine(): int
    {
        $due = $this->due_date->copy()->startOfDay();

        $end = ($this->status === 'returned' && $this->return_date)
            ? $this->return_date->copy()->startOfDay()
            : now()->startOfDay();

        if ($end->gt($due)) {
            return (int) round($due->diffInDays($end)) * self::FINE_PER_DAY;
        }

        return 0;
    }

    /** Jumlah hari terlambat (0 jika belum lewat jatuh tempo). */
    public function daysLate(): int
    {
        $due = $this->due_date->copy()->startOfDay();
        $end = ($this->status === 'returned' && $this->return_date)
            ? $this->return_date->copy()->startOfDay()
            : now()->startOfDay();

        return $end->gt($due) ? (int) round($due->diffInDays($end)) : 0;
    }

    /**
     * Sinkronkan status 'overdue' dan denda berjalan untuk semua peminjaman
     * yang sudah lewat jatuh tempo dan belum dikembalikan.
     */
    public static function refreshOverdue(): void
    {
        static::whereIn('status', ['active', 'overdue'])
            ->whereDate('due_date', '<', now()->toDateString())
            ->get()
            ->each(function (Loan $loan) {
                $fine = $loan->calculateFine();

                if ($loan->status !== 'overdue' || (int) $loan->fine !== $fine) {
                    $loan->update(['status' => 'overdue', 'fine' => $fine]);
                }
            });
    }
}
