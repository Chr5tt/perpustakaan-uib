@extends('layouts.app')
@section('title', 'Reservasi Buku')
@section('page-title', 'Reservasi Buku')

@section('content')
<div class="page-header">
    <div>
        <h1>Daftar Reservasi</h1>
        <p>Kelola antrean reservasi peminjaman buku</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Antrean Reservasi</div>
            <div class="card-subtitle">Menampilkan reservasi aktif dan riwayat antrean</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                    <th>Peminjam</th>
                    @endif
                    <th>Tgl Reservasi</th>
                    <th>Berlaku Sampai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $i => $res)
                <tr>
                    <td>{{ ($reservations->currentPage() - 1) * $reservations->perPage() + $i + 1 }}</td>
                    <td>
                        <div style="font-weight: 600; font-size: 13.5px;">{{ $res->book->title }}</div>
                        <div style="font-size: 11.5px; color: var(--text-muted);">{{ $res->book->author }}</div>
                    </td>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                    <td>
                        <div style="font-size: 13px; font-weight: 500;">{{ $res->user->name }}</div>
                        <div style="font-size: 11.5px; color: var(--text-muted);">{{ $res->user->nim_nidn }} ({{ ucfirst($res->user->role) }})</div>
                    </td>
                    @endif
                    <td style="font-size: 13px;">{{ \Carbon\Carbon::parse($res->reservation_date)->format('d M Y') }}</td>
                    <td style="font-size: 13px;">{{ \Carbon\Carbon::parse($res->expiry_date)->format('d M Y') }}</td>
                    <td>
                        @if($res->status === 'waiting') <span class="badge badge-blue">Menunggu Antrean</span>
                        @elseif($res->status === 'ready') <span class="badge badge-green">Siap Diambil</span>
                        @elseif($res->status === 'completed') <span class="badge badge-gray">Selesai</span>
                        @else <span class="badge badge-red">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($res->status, ['waiting', 'ready']))
                            @if(auth()->id() === $res->user_id || auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                            <form action="{{ route('reservations.destroy', $res) }}" method="POST" style="display: inline;"
                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Batalkan Reservasi">
                                    <i class="fas fa-times"></i> Batalkan
                                </button>
                            </form>
                            @endif
                        @else
                            <span style="color: var(--text-muted); font-size: 12px;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);">
                        Tidak ada antrean reservasi aktif.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $reservations->links() }}
    </div>
</div>
@endsection
