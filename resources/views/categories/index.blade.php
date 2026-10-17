@extends('layouts.app')
@section('title', 'Kategori Buku')
@section('page-title', 'Kategori Buku')

@section('content')
<div class="page-header">
    <div>
        <h1>Kategori Buku</h1>
        <p>Kelola klasifikasi kategori buku di perpustakaan</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start;">
    <!-- CATEGORY LIST -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Daftar Kategori</div>
                <div class="card-subtitle">Menampilkan {{ $categories->total() }} kategori terdaftar</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $i => $cat)
                    <tr>
                        <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $i + 1 }}</td>
                        <td style="font-weight: 600;">{{ $cat->name }}</td>
                        <td style="font-family: monospace; font-size: 12px; color: var(--text-muted);">{{ $cat->slug }}</td>
                        <td style="font-size: 13px; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $cat->description ?? '-' }}
                        </td>
                        <td>
                            <span class="badge badge-blue">{{ $cat->books_count }} buku</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="btn btn-sm btn-outline btn-icon" title="Edit"
                                    onclick="openEditModal({{ json_encode($cat) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" style="display: inline;"
                                    onsubmit="return confirm('Hapus kategori ini? Buku yang terhubung tidak akan terhapus jika kategori masih kosong.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                            Belum ada kategori buku.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 16px;">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- ADD CATEGORY FORM -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Tambah Kategori</div>
        </div>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori <span>*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Sains & Teknologi" required value="{{ old('name') }}">
                @error('name')<div style="color: var(--red); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi singkat kategori...">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <i class="fas fa-plus"></i> Tambah Kategori
            </button>
        </form>
    </div>
</div>

<!-- EDIT CATEGORY MODAL -->
<div class="modal-overlay" id="edit-modal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Kategori Buku</h3>
            <button type="button" class="modal-close" onclick="closeEditModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="edit-form" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Kategori <span>*</span></label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="edit-description" class="form-control" rows="4"></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEditModal(category) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    const nameInput = document.getElementById('edit-name');
    const descInput = document.getElementById('edit-description');
    
    // Set form action
    form.action = "{{ url('/categories') }}/" + category.id;
    
    // Populate values
    nameInput.value = category.name;
    descInput.value = category.description || '';
    
    // Show modal
    modal.classList.add('show');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.remove('show');
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('edit-modal');
    if (e.target === modal) {
        closeEditModal();
    }
});
</script>
@endpush
