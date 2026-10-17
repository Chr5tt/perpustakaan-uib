@extends('layouts.app')
@section('title', 'Peminjaman')
@section('page-title', 'Peminjaman')

@section('content')
<div class="page-header">
    <div>
        <h1>Data Peminjaman</h1>
        <p>Kelola transaksi peminjaman buku perpustakaan</p>
    </div>
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
    <a href="{{ route('loans.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Pinjam Buku
    </a>
    @endif
</div>

<!-- FILTER TABS -->
<div class="tabs">
    <a href="{{ route('loans.index') }}" class="tab-btn {{ !request('status') ? 'active' : '' }}">Semua</a>
    <a href="{{ route('loans.index', ['status' => 'active']) }}" class="tab-btn {{ request('status') === 'active' ? 'active' : '' }}">
        Aktif @if($counts['active'] > 0) <span class="badge badge-green" style="margin-left:4px;">{{ $counts['active'] }}</span> @endif
    </a>
    <a href="{{ route('loans.index', ['status' => 'overdue']) }}" class="tab-btn {{ request('status') === 'overdue' ? 'active' : '' }}">
        Terlambat @if($counts['overdue'] > 0) <span class="badge badge-red" style="margin-left:4px;">{{ $counts['overdue'] }}</span> @endif
    </a>
    <a href="{{ route('loans.index', ['status' => 'returned']) }}" class="tab-btn {{ request('status') === 'returned' ? 'active' : '' }}">Dikembalikan</a>
    <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="tab-btn {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
</div>

<div class="card">
    <!-- SEARCH -->
    <form action="{{ route('loans.index') }}" method="GET" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div class="search-box" style="flex:1; min-width:180px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari nama, kode pinjam, judul buku..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
        @if(request('search'))<a href="{{ route('loans.index', ['status' => request('status')]) }}" class="btn btn-outline">Reset</a>@endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode Pinjam</th>
                    <th>Buku</th>
                    <th>Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td style="font-family:monospace; font-size:12px; font-weight:600; color:var(--navy);">{{ $loan->loan_code }}</td>
                    <td>
                        <div style="font-weight:600; font-size:13px;">{{ Str::limit($loan->book->title, 30) }}</div>
                        <div style="font-size:11.5px; color:#64748b;">{{ $loan->book->author }}</div>
                    </td>
                    <td>
                        <div style="font-size:13px; font-weight:500;">{{ $loan->user->name }}</div>
                        <div style="font-size:11.5px; color:#64748b;">{{ $loan->user->nim_nidn }}</div>
                    </td>
                    <td style="font-size:13px;">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                    <td style="font-size:13px;">
                        {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                        @if($loan->status === 'overdue')
                        <div style="font-size:11px; color:var(--red); font-weight:600;">
                            {{ \Carbon\Carbon::parse($loan->due_date)->diffForHumans() }}
                        </div>
                        @endif
                    </td>
                    <td>
                        @if($loan->status === 'active') <span class="badge badge-green">Aktif</span>
                        @elseif($loan->status === 'overdue') <span class="badge badge-red">Terlambat</span>
                        @elseif($loan->status === 'returned') <span class="badge badge-gray">Kembali</span>
                        @else <span class="badge badge-blue">Pending</span>
                        @endif
                    </td>
                    <td style="font-size:13px;">
                        @if($loan->fine > 0)
                        <span style="color:var(--red); font-weight:600;">Rp {{ number_format($loan->fine, 0, ',', '.') }}</span>
                        @else
                        <span style="color:#64748b;">-</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-outline btn-icon" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                                @if(in_array($loan->status, ['active', 'overdue']))
                                <form action="{{ route('loans.return', $loan) }}" method="POST" style="display:inline;" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success btn-icon" title="Kembalikan">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                @endif
                                @if($loan->status === 'pending')
                                <form action="{{ route('loans.approve', $loan) }}" method="POST" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-accent btn-icon" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-hand-holding-heart"></i>
                            <p>Tidak ada data peminjaman</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">{{ $loans->links() }}</div>
</div>
@endsection
