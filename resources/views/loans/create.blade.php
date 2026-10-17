@extends('layouts.app')
@section('title', 'Pinjam Buku')
@section('page-title', 'Pinjam Buku')

@section('content')
<div style="max-width:600px;">
    <div class="page-header">
        <div>
            <h1>Form Peminjaman</h1>
            <p>Isi data peminjaman buku</p>
        </div>
        <a href="{{ route('loans.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Anggota <span>*</span></label>
                <select name="user_id" class="form-control" required>
                    <option value="">Pilih Anggota</option>
                    @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ old('user_id', request('user_id')) == $member->id ? 'selected' : '' }}>
                        {{ $member->name }} — {{ $member->nim_nidn }} ({{ ucfirst($member->role) }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Buku <span>*</span></label>
                <select name="book_id" class="form-control" required>
                    <option value="">Pilih Buku</option>
                    @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id', request('book_id')) == $book->id ? 'selected' : '' }}
                        {{ $book->available <= 0 ? 'disabled' : '' }}>
                        {{ $book->title }} — {{ $book->author }}
                        (Tersedia: {{ $book->available }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal Pinjam <span>*</span></label>
                    <input type="date" name="loan_date" class="form-control" value="{{ old('loan_date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Jatuh Tempo <span>*</span></label>
                    <input type="date" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan opsional...">{{ old('notes') }}</textarea>
            </div>

            @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach($errors->all() as $e)
                    <div>{{ $e }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <a href="{{ route('loans.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-accent">
                    <i class="fas fa-hand-holding"></i> Pinjamkan Buku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
