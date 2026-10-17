@extends('layouts.app')
@section('title', isset($member) ? 'Edit Anggota' : 'Tambah Anggota')
@section('page-title', isset($member) ? 'Edit Anggota' : 'Tambah Anggota')

@section('content')
<div style="max-width: 700px;">
    <div class="page-header">
        <div>
            <h1>{{ isset($member) ? 'Edit Anggota' : 'Tambah Anggota Baru' }}</h1>
            <p>{{ isset($member) ? 'Perbarui profil data anggota perpustakaan' : 'Tambahkan anggota baru ke database perpustakaan' }}</p>
        </div>
        <a href="{{ route('members.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <form action="{{ isset($member) ? route('members.update', $member) : route('members.store') }}" method="POST">
            @csrf
            @if(isset($member)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group" style="grid-column: 1/-1;">
                    <label class="form-label">Nama Lengkap <span>*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required
                        value="{{ old('name', $member->name ?? '') }}">
                    @error('name')<div style="color: var(--red); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span>*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="email@uib.ac.id" required
                        value="{{ old('email', $member->email ?? '') }}">
                    @error('email')<div style="color: var(--red); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NIM / NIDN</label>
                    <input type="text" name="nim_nidn" class="form-control" placeholder="Contoh: 2112001"
                        value="{{ old('nim_nidn', $member->nim_nidn ?? '') }}">
                    @error('nim_nidn')<div style="color: var(--red); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No. Telepon / HP</label>
                    <input type="text" name="phone" class="form-control" placeholder="Contoh: 0812xxxxxxxx"
                        value="{{ old('phone', $member->phone ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Program Studi</label>
                    <input type="text" name="program_studi" class="form-control" placeholder="Contoh: Sistem Informasi"
                        value="{{ old('program_studi', $member->program_studi ?? '') }}">
                </div>

                <div class="form-group" style="grid-column: 1/-1;">
                    <label class="form-label">Role (Hak Akses) <span>*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="mahasiswa" {{ old('role', $member->role ?? '') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ old('role', $member->role ?? '') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="staff" {{ old('role', $member->role ?? '') === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ old('role', $member->role ?? '') === 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                    </select>
                    @error('role')<div style="color: var(--red); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>

                @if(!isset($member))
                <div class="form-group" style="grid-column: 1/-1;">
                    <label class="form-label">Password <span>*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password untuk login pertama kali (min. 6 karakter)" required>
                    @error('password')<div style="color: var(--red); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>
                @else
                <div class="form-group" style="grid-column: 1/-1;">
                    <label class="form-label">Password Baru <span style="font-weight:400; color:var(--text-muted);">(opsional)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password (min. 6 karakter)">
                    @error('password')<div style="color: var(--red); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>
                @endif
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <a href="{{ route('members.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($member) ? 'Simpan Perubahan' : 'Tambah Anggota' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
