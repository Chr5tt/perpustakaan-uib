@extends('layouts.app')
@section('title', 'Edit Peminjaman')
@section('page-title', 'Edit Peminjaman')

@section('content')
<div style="max-width:600px;">
    <div class="page-header">
        <div>
            <h1>Edit Peminjaman</h1>
            <p>Perbarui data peminjaman {{ $loan->loan_code }}</p>
        </div>
        <a href="{{ route('loans.show', $loan) }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <div style="background:#f8fafc; border-radius:10px; padding:16px; margin-bottom:20px; border:1px solid var(--border);">
            <div style="font-size:12px; color:#64748b; margin-bottom:4px;">Buku</div>
            <div style="font-weight:700;">{{ $loan->book->title }}</div>
            <div style="font-size:12px; color:#64748b;">Peminjam: {{ $loan->user->name }}</div>
        </div>

        <form action="{{ route('loans.update', $loan) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" value="{{ $loan->loan_date->format('Y-m-d') }}" disabled style="background:#f8fafc;">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Jatuh Tempo <span>*</span></label>
                    <input type="date" name="due_date" class="form-control"
                        value="{{ old('due_date', $loan->due_date->format('Y-m-d')) }}"
                        min="{{ $loan->loan_date->format('Y-m-d') }}" required>
                    @error('due_date')<div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $loan->notes) }}</textarea>
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <a href="{{ route('loans.show', $loan) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
