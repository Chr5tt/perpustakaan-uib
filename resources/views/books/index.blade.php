@extends('layouts.app')
@section('title', 'Koleksi Buku')
@section('page-title', 'Koleksi Buku')

@section('content')
<div class="page-header">
    <div>
        <h1>Koleksi Buku</h1>
        <p>{{ $books->total() }} judul tersedia di perpustakaan UIB</p>
    </div>
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
    <a href="{{ route('books.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Buku
    </a>
    @endif
</div>

<!-- VIEW TOGGLE + SEARCH -->
<div class="card" style="margin-bottom:20px; padding:16px;">
    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <div style="display:flex; border:1.5px solid var(--border); border-radius:9px; overflow:hidden;">
            <button id="btn-grid" onclick="setView('grid')" class="btn btn-sm" style="border-radius:0; border:none; background:var(--navy); color:white;">
                <i class="fas fa-th"></i>
            </button>
            <button id="btn-list" onclick="setView('list')" class="btn btn-sm" style="border-radius:0; border:none; background:white; color:var(--text-muted);">
                <i class="fas fa-list"></i>
            </button>
        </div>

        <form action="{{ route('books.index') }}" method="GET" style="display:flex; gap:10px; flex:1; flex-wrap:wrap;">
            <div class="search-box" style="flex:1; min-width:180px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari judul, penulis, ISBN..." value="{{ request('search') }}">
            </div>
            <select name="category" class="form-control" style="width:180px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="available" class="form-control" style="width:150px;">
                <option value="">Semua Status</option>
                <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ request('available') == '0' ? 'selected' : '' }}>Dipinjam</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request()->hasAny(['search','category','available']))
            <a href="{{ route('books.index') }}" class="btn btn-outline">Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- GRID VIEW -->
<div id="view-grid" class="book-grid">
    @forelse($books as $book)
    <div class="book-card" onclick="window.location='{{ route('books.show', $book) }}'">
        <div class="book-cover">
            @if($book->cover)
            <img src="{{ asset('storage/'.$book->cover) }}" alt="{{ $book->title }}">
            @else
            <i class="fas fa-book book-cover-placeholder"></i>
            @endif
            @if($book->available > 0)
            <span class="book-cover-badge badge-green badge">Tersedia</span>
            @else
            <span class="book-cover-badge badge-red badge">Habis</span>
            @endif
        </div>
        <div class="book-info">
            <div class="book-title">{{ $book->title }}</div>
            <div class="book-author">{{ $book->author }}</div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:8px;">
                <span class="book-category">{{ $book->category->name }}</span>
                <span style="font-size:11px; color:#64748b;">{{ $book->year }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column:1/-1;">
        <i class="fas fa-book"></i>
        <p>Tidak ada buku ditemukan</p>
    </div>
    @endforelse
</div>

<!-- LIST VIEW -->
<div id="view-list" style="display:none;">
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td style="font-size:12px; font-family:monospace; color:#64748b;">{{ $book->isbn }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $book->title }}</div>
                            <div style="font-size:11.5px; color:#64748b;">{{ $book->publisher }}</div>
                        </td>
                        <td>{{ $book->author }}</td>
                        <td><span class="badge badge-blue">{{ $book->category->name }}</span></td>
                        <td>{{ $book->year }}</td>
                        <td>{{ $book->available }}/{{ $book->stock }}</td>
                        <td>
                            @if($book->available > 0)
                                <span class="badge badge-green">Tersedia</span>
                            @else
                                <span class="badge badge-red">Habis</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus buku ini?')">
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
                    <tr><td colspan="8" style="text-align:center; padding:24px; color:#64748b;">Tidak ada buku ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $books->links() }}</div>
    </div>
</div>

<!-- Pagination for grid -->
<div id="grid-pagination" style="margin-top:24px;">
    {{ $books->links() }}
</div>

@endsection

@push('scripts')
<script>
function setView(type) {
    if (type === 'grid') {
        document.getElementById('view-grid').style.display = 'grid';
        document.getElementById('view-list').style.display = 'none';
        document.getElementById('grid-pagination').style.display = 'block';
        document.getElementById('btn-grid').style.background = 'var(--navy)';
        document.getElementById('btn-grid').style.color = 'white';
        document.getElementById('btn-list').style.background = 'white';
        document.getElementById('btn-list').style.color = 'var(--text-muted)';
        localStorage.setItem('bookView', 'grid');
    } else {
        document.getElementById('view-grid').style.display = 'none';
        document.getElementById('view-list').style.display = 'block';
        document.getElementById('grid-pagination').style.display = 'none';
        document.getElementById('btn-list').style.background = 'var(--navy)';
        document.getElementById('btn-list').style.color = 'white';
        document.getElementById('btn-grid').style.background = 'white';
        document.getElementById('btn-grid').style.color = 'var(--text-muted)';
        localStorage.setItem('bookView', 'list');
    }
}

// Restore view preference
const saved = localStorage.getItem('bookView');
if (saved === 'list') setView('list');
</script>
@endpush
