@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div class="page-header">
        <div>
            <h1>Transaksi: {{ $loan->loan_code }}</h1>
            <p>Detail sirkulasi peminjaman buku</p>
        </div>
        <a href="{{ route('loans.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start;">
        <!-- SIRKULASI DETAILS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informasi Peminjaman</div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Status</span>
                    @if($loan->status === 'active') <span class="badge badge-green" style="font-size: 13px; padding: 4px 12px;">Aktif</span>
                    @elseif($loan->status === 'overdue') <span class="badge badge-red" style="font-size: 13px; padding: 4px 12px;">Terlambat</span>
                    @elseif($loan->status === 'returned') <span class="badge badge-gray" style="font-size: 13px; padding: 4px 12px;">Kembali</span>
                    @else <span class="badge badge-blue" style="font-size: 13px; padding: 4px 12px;">Pending</span>
                    @endif
                </div>

                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Tanggal Pinjam</span>
                    <strong style="font-size: 14.5px;">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</strong>
                </div>

                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Tanggal Jatuh Tempo</span>
                    <strong style="font-size: 14.5px; color: {{ $loan->status === 'overdue' ? 'var(--red)' : 'inherit' }}">
                        {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                    </strong>
                    @if(in_array($loan->status, ['active', 'overdue']) && $loan->daysLate() > 0)
                    <span style="font-size: 11.5px; color: var(--red); display: block; font-weight: 600; margin-top: 2px;">
                        Terlambat {{ $loan->daysLate() }} hari
                    </span>
                    @endif
                </div>

                @if($loan->return_date)
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Tanggal Dikembalikan</span>
                    <strong style="font-size: 14.5px; color: var(--green);">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</strong>
                </div>
                @endif

                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Denda Terakumulasi</span>
                    @if($loan->fine > 0)
                    <strong style="font-size: 18px; color: var(--red);">Rp {{ number_format($loan->fine, 0, ',', '.') }}</strong>
                    @elseif($loan->status === 'active' || $loan->status === 'overdue')
                    <strong style="font-size: 15px; color: var(--red);">
                        Rp {{ number_format($loan->calculateFine(), 0, ',', '.') }}
                    </strong>
                    <span style="font-size: 11px; color: var(--text-muted); display: block;">(Dihitung Rp 1.000 per hari keterlambatan)</span>
                    @else
                    <strong style="font-size: 14.5px; color: var(--text-muted);">-</strong>
                    @endif
                </div>

                @if($loan->processedBy)
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Diproses Oleh Staff</span>
                    <strong style="font-size: 14.5px;">{{ $loan->processedBy->name }}</strong>
                </div>
                @endif

                @if($loan->notes)
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Catatan</span>
                    <p style="font-size: 13.5px; color: #475569; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid var(--border); margin-top: 4px;">
                        {{ $loan->notes }}
                    </p>
                </div>
                @endif
            </div>

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
            <div style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 16px; display: flex; gap: 10px;">
                @if(in_array($loan->status, ['active', 'overdue']))
                <form action="{{ route('loans.return', $loan) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success" style="width: 100%; justify-content: center;">
                        <i class="fas fa-undo"></i> Kembalikan Buku
                    </button>
                </form>
                @endif

                @if($loan->status === 'pending')
                <form action="{{ route('loans.approve', $loan) }}" method="POST" style="flex: 1;">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-accent" style="width: 100%; justify-content: center;">
                        <i class="fas fa-check"></i> Setujui Peminjaman
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>

        <!-- BOOK & USER DETAILS -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Book Details Card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informasi Buku</div>
                </div>
                <div style="display: flex; gap: 16px;">
                    <div style="width: 80px; height: 110px; border-radius: 8px; background: linear-gradient(135deg,var(--navy) 0%,var(--navy-light) 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                        @if($loan->book->cover)
                        <img src="{{ asset('storage/'.$loan->book->cover) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                        <i class="fas fa-book" style="font-size: 24px; color: rgba(255,255,255,0.3);"></i>
                        @endif
                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                            <a href="{{ route('books.show', $loan->book) }}" style="color: var(--navy); text-decoration: none; hover: underline;">
                                {{ $loan->book->title }}
                            </a>
                        </h3>
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">oleh {{ $loan->book->author }}</p>
                        <p style="font-size: 12px; font-family: monospace; color: var(--text-muted);">ISBN: {{ $loan->book->isbn }}</p>
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Rak: {{ $loan->book->location ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- User Details Card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informasi Peminjam</div>
                </div>
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--navy); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: white; flex-shrink: 0;">
                        {{ strtoupper(substr($loan->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 2px;">
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('members.show', $loan->user) }}" style="color: var(--navy); text-decoration: none;">
                                {{ $loan->user->name }}
                            </a>
                            @else
                            {{ $loan->user->name }}
                            @endif
                        </h3>
                        <p style="font-size: 12px; color: var(--text-muted); font-family: monospace;">NIM/NIDN: {{ $loan->user->nim_nidn ?? '-' }}</p>
                        <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 2px;">
                            {{ ucfirst($loan->user->role) }} @if($loan->user->program_studi) | {{ $loan->user->program_studi }} @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
