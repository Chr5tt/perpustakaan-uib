@extends('layouts.app')
@section('title', 'Anggota')
@section('page-title', 'Anggota')

@section('content')
<div class="page-header">
    <div>
        <h1>Data Anggota</h1>
        <p>{{ $members->total() }} anggota terdaftar</p>
    </div>
    <a href="{{ route('members.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Tambah Anggota
    </a>
</div>

<div class="card">
    <form action="{{ route('members.index') }}" method="GET" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <div class="search-box" style="flex:1; min-width:200px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari nama, email, NIM/NIDN..." value="{{ request('search') }}">
        </div>
        <select name="role" class="form-control" style="width:160px;">
            <option value="">Semua Role</option>
            <option value="mahasiswa" {{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            <option value="dosen" {{ request('role') === 'dosen' ? 'selected' : '' }}>Dosen</option>
            <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
        @if(request()->hasAny(['search','role']))<a href="{{ route('members.index') }}" class="btn btn-outline">Reset</a>@endif
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>NIM/NIDN</th>
                    <th>Program Studi</th>
                    <th>No. HP</th>
                    <th>Role</th>
                    <th>Dipinjam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:34px; height:34px; border-radius:50%; background:var(--navy); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; color:white; flex-shrink:0;">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600; font-size:13px;">{{ $member->name }}</div>
                                <div style="font-size:11.5px; color:#64748b;">{{ $member->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace; font-size:13px;">{{ $member->nim_nidn ?? '-' }}</td>
                    <td style="font-size:13px;">{{ $member->program_studi ?? '-' }}</td>
                    <td style="font-size:13px;">{{ $member->phone ?? '-' }}</td>
                    <td>
                        @if($member->role === 'admin') <span class="badge badge-purple">Admin</span>
                        @elseif($member->role === 'staff') <span class="badge badge-blue">Staff</span>
                        @elseif($member->role === 'dosen') <span class="badge badge-accent">Dosen</span>
                        @else <span class="badge badge-gray">Mahasiswa</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight:600; font-size:13px;">{{ $member->loans()->whereIn('status',['active','overdue'])->count() }}</span>
                        <span style="font-size:11.5px; color:#64748b;"> aktif</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline btn-icon" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if((int) $member->id !== (int) auth()->id())
                            <form action="{{ route('members.destroy', $member) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus anggota ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Tidak ada anggota ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">{{ $members->links() }}</div>
</div>
@endsection
