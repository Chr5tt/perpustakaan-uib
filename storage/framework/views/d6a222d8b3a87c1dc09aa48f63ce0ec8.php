<?php $__env->startSection('title', 'Koleksi Buku'); ?>
<?php $__env->startSection('page-title', 'Koleksi Buku'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Koleksi Buku</h1>
        <p><?php echo e($books->total()); ?> judul tersedia di perpustakaan UIB</p>
    </div>
    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
    <a href="<?php echo e(route('books.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Buku
    </a>
    <?php endif; ?>
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

        <form action="<?php echo e(route('books.index')); ?>" method="GET" style="display:flex; gap:10px; flex:1; flex-wrap:wrap;">
            <div class="search-box" style="flex:1; min-width:180px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari judul, penulis, ISBN..." value="<?php echo e(request('search')); ?>">
            </div>
            <select name="category" class="form-control" style="width:180px;">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="available" class="form-control" style="width:150px;">
                <option value="">Semua Status</option>
                <option value="1" <?php echo e(request('available') == '1' ? 'selected' : ''); ?>>Tersedia</option>
                <option value="0" <?php echo e(request('available') == '0' ? 'selected' : ''); ?>>Dipinjam</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
            <?php if(request()->hasAny(['search','category','available'])): ?>
            <a href="<?php echo e(route('books.index')); ?>" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- GRID VIEW -->
<div id="view-grid" class="book-grid">
    <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="book-card" onclick="window.location='<?php echo e(route('books.show', $book)); ?>'">
        <div class="book-cover">
            <?php if($book->cover): ?>
            <img src="<?php echo e(asset('storage/'.$book->cover)); ?>" alt="<?php echo e($book->title); ?>">
            <?php else: ?>
            <i class="fas fa-book book-cover-placeholder"></i>
            <?php endif; ?>
            <?php if($book->available > 0): ?>
            <span class="book-cover-badge badge-green badge">Tersedia</span>
            <?php else: ?>
            <span class="book-cover-badge badge-red badge">Habis</span>
            <?php endif; ?>
        </div>
        <div class="book-info">
            <div class="book-title"><?php echo e($book->title); ?></div>
            <div class="book-author"><?php echo e($book->author); ?></div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:8px;">
                <span class="book-category"><?php echo e($book->category->name); ?></span>
                <span style="font-size:11px; color:#64748b;"><?php echo e($book->year); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state" style="grid-column:1/-1;">
        <i class="fas fa-book"></i>
        <p>Tidak ada buku ditemukan</p>
    </div>
    <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-size:12px; font-family:monospace; color:#64748b;"><?php echo e($book->isbn); ?></td>
                        <td>
                            <div style="font-weight:600;"><?php echo e($book->title); ?></div>
                            <div style="font-size:11.5px; color:#64748b;"><?php echo e($book->publisher); ?></div>
                        </td>
                        <td><?php echo e($book->author); ?></td>
                        <td><span class="badge badge-blue"><?php echo e($book->category->name); ?></span></td>
                        <td><?php echo e($book->year); ?></td>
                        <td><?php echo e($book->available); ?>/<?php echo e($book->stock); ?></td>
                        <td>
                            <?php if($book->available > 0): ?>
                                <span class="badge badge-green">Tersedia</span>
                            <?php else: ?>
                                <span class="badge badge-red">Habis</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="<?php echo e(route('books.show', $book)); ?>" class="btn btn-sm btn-outline btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
                                <a href="<?php echo e(route('books.edit', $book)); ?>" class="btn btn-sm btn-outline btn-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('books.destroy', $book)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus buku ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" style="text-align:center; padding:24px; color:#64748b;">Tidak ada buku ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;"><?php echo e($books->links()); ?></div>
    </div>
</div>

<!-- Pagination for grid -->
<div id="grid-pagination" style="margin-top:24px;">
    <?php echo e($books->links()); ?>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/books/index.blade.php ENDPATH**/ ?>