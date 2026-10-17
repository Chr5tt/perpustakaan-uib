@extends('layouts.app')
@section('title', 'Detail Anggota')
@section('page-title', 'Detail Anggota')

@section('content')
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 20px; align-items: start;">
    <!-- MEMBER PROFILE CARD -->
    <div class="card">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 10px 0;">
            <div style="width: 90px; height: 90px; border-radius: 50%; background: var(--navy); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; color: white; margin-bottom: 16px;">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 4px;">{{ $member->name }}</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">{{ $member->email }}</p>
            
            <div style="display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; margin-bottom: 24px;">
                @if($member->role === 'admin') <span class="badge badge-purple">Admin</span>
                @elseif($member->role === 'staff') <span class="badge badge-blue">Staff</span>
                @elseif($member->role === 'dosen') <span class="badge badge-accent">Dosen</span>
                @else <span class="badge badge-gray">Mahasiswa</span>
                @endif
                
                @if($member->program_studi)
                <span class="badge badge-gray">{{ $member->program_studi }}</span>
                @endif
            </div>
            
            <div style="width: 100%; border-top: 1px solid var(--border); padding-top: 16px; text-align: left;">
                <div style="margin-bottom: 12px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">NIM / NIDN</div>
                    <div style="font-size: 13.5px; font-weight: 600; font-family: monospace;">{{ $member->nim_nidn ?? '-' }}</div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">No. Telepon / HP</div>
                    <div style="font-size: 13.5px; font-weight: 600;">{{ $member->phone ?? '-' }}</div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Status Peminjaman Aktif</div>
                    <div style="font-size: 13.5px; font-weight: 600;">
                        {{ $member->loans()->whereIn('status', ['active', 'overdue'])->count() }} buku dipinjam
                    </div>
                </div>
            </div>
            
            <div style="width: 100%; display: flex; flex-direction: column; gap: 8px; margin-top: 16px;">
                <a href="{{ route('members.edit', $member) }}" class="btn btn-outline" style="justify-content: center;">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
                <a href="{{ route('members.index') }}" class="btn btn-outline" style="justify-content: center;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- MEMBER SIRKULASI / RIWAYAT LOANS -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Riwayat Sirkulasi Peminjaman</div>
                <div class="card-subtitle">Menampilkan riwayat peminjaman buku anggota ini</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali / Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($member->loans()->latest()->get() as $loan)
                    <tr>
                        <td style="font-family: monospace; font-size: 12px; font-weight: 600; color: var(--navy);">
                            {{ $loan->loan_code }}
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ Str::limit($loan->book->title, 40) }}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted);">{{ $loan->book->author }}</div>
                        </td>
                        <td style="font-size: 13px;">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                        <td style="font-size: 13px;">
                            @if($loan->status === 'returned')
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                @if($loan->status === 'overdue')
                                <span style="display: block; font-size: 11px; color: var(--red); font-weight: 600;">Terlambat</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($loan->status === 'active') <span class="badge badge-green">Aktif</span>
                            @elseif($loan->status === 'overdue') <span class="badge badge-red">Terlambat</span>
                            @elseif($loan->status === 'returned') <span class="badge badge-gray">Kembali</span>
                            @else <span class="badge badge-blue">Pending</span>
                            @endif
                        </td>
                        <td style="font-size: 13px;">
                            @if($loan->fine > 0)
                            <span style="color: var(--red); font-weight: 600;">Rp {{ number_format($loan->fine, 0, ',', '.') }}</span>
                            @else -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                            Anggota ini belum pernah meminjam buku.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
