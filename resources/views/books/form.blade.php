@extends('layouts.app')
@section('title', isset($book) ? 'Edit Buku' : 'Tambah Buku')
@section('page-title', isset($book) ? 'Edit Buku' : 'Tambah Buku')

@section('content')
<div style="max-width:700px;">
    <div class="page-header">
        <div>
            <h1>{{ isset($book) ? 'Edit Buku' : 'Tambah Buku Baru' }}</h1>
            <p>{{ isset($book) ? 'Perbarui informasi buku' : 'Masukkan data buku baru ke perpustakaan' }}</p>
        </div>
        <a href="{{ route('books.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <form action="{{ isset($book) ? route('books.update', $book) : route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($book)) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Judul Buku <span>*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $book->title ?? '') }}" required placeholder="Masukkan judul buku">
                    @error('title')<div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Penulis <span>*</span></label>
                    <input type="text" name="author" class="form-control" value="{{ old('author', $book->author ?? '') }}" required placeholder="Nama penulis">
                    @error('author')<div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">ISBN <span>*</span></label>
                    <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn ?? '') }}" required placeholder="978-xxxxxxxxxx">
                    @error('isbn')<div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Penerbit <span>*</span></label>
                    <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $book->publisher ?? '') }}" required placeholder="Nama penerbit">
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun Terbit <span>*</span></label>
                    <input type="number" name="year" class="form-control" value="{{ old('year', $book->year ?? date('Y')) }}" min="1800" max="{{ date('Y') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori <span>*</span></label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Stok <span>*</span></label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $book->stock ?? 1) }}" min="1" required>
                    @error('stock')<div style="color:var(--red); font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi Rak</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $book->location ?? '') }}" placeholder="Contoh: Rak A1">
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi singkat buku...">{{ old('description', $book->description ?? '') }}</textarea>
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Cover Buku</label>
                    @if(isset($book) && $book->cover)
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('storage/'.$book->cover) }}" style="height:80px; border-radius:8px; object-fit:cover;">
                    </div>
                    @endif
                    <input type="file" name="cover" class="form-control" accept="image/*">
                    <div style="font-size:12px; color:#64748b; margin-top:4px;">Format: JPG, PNG, WEBP. Max 2MB.</div>
                </div>
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
                <a href="{{ route('books.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($book) ? 'Simpan Perubahan' : 'Tambah Buku' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
