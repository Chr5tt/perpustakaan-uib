<?php $__env->startSection('title', 'Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Data Peminjaman</h1>
        <p>Kelola transaksi peminjaman buku perpustakaan</p>
    </div>
    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
    <a href="<?php echo e(route('loans.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Pinjam Buku
    </a>
    <?php endif; ?>
</div>

<!-- FILTER TABS -->
<div class="tabs">
    <a href="<?php echo e(route('loans.index')); ?>" class="tab-btn <?php echo e(!request('status') ? 'active' : ''); ?>">Semua</a>
    <a href="<?php echo e(route('loans.index', ['status' => 'active'])); ?>" class="tab-btn <?php echo e(request('status') === 'active' ? 'active' : ''); ?>">
        Aktif <?php if($counts['active'] > 0): ?> <span class="badge badge-green" style="margin-left:4px;"><?php echo e($counts['active']); ?></span> <?php endif; ?>
    </a>
    <a href="<?php echo e(route('loans.index', ['status' => 'overdue'])); ?>" class="tab-btn <?php echo e(request('status') === 'overdue' ? 'active' : ''); ?>">
        Terlambat <?php if($counts['overdue'] > 0): ?> <span class="badge badge-red" style="margin-left:4px;"><?php echo e($counts['overdue']); ?></span> <?php endif; ?>
    </a>
    <a href="<?php echo e(route('loans.index', ['status' => 'returned'])); ?>" class="tab-btn <?php echo e(request('status') === 'returned' ? 'active' : ''); ?>">Dikembalikan</a>
    <a href="<?php echo e(route('loans.index', ['status' => 'pending'])); ?>" class="tab-btn <?php echo e(request('status') === 'pending' ? 'active' : ''); ?>">Pending</a>
</div>

<div class="card">
    <!-- SEARCH -->
    <form action="<?php echo e(route('loans.index')); ?>" method="GET" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
        <div class="search-box" style="flex:1; min-width:180px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari nama, kode pinjam, judul buku..." value="<?php echo e(request('search')); ?>">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
        <?php if(request('search')): ?><a href="<?php echo e(route('loans.index', ['status' => request('status')])); ?>" class="btn btn-outline">Reset</a><?php endif; ?>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode Pinjam</th>
                    <th>Buku</th>
                    <th>Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family:monospace; font-size:12px; font-weight:600; color:var(--navy);"><?php echo e($loan->loan_code); ?></td>
                    <td>
                        <div style="font-weight:600; font-size:13px;"><?php echo e(Str::limit($loan->book->title, 30)); ?></div>
                        <div style="font-size:11.5px; color:#64748b;"><?php echo e($loan->book->author); ?></div>
                    </td>
                    <td>
                        <div style="font-size:13px; font-weight:500;"><?php echo e($loan->user->name); ?></div>
                        <div style="font-size:11.5px; color:#64748b;"><?php echo e($loan->user->nim_nidn); ?></div>
                    </td>
                    <td style="font-size:13px;"><?php echo e(\Carbon\Carbon::parse($loan->loan_date)->format('d M Y')); ?></td>
                    <td style="font-size:13px;">
                        <?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?>

                        <?php if($loan->status === 'overdue'): ?>
                        <div style="font-size:11px; color:var(--red); font-weight:600;">
                            <?php echo e(\Carbon\Carbon::parse($loan->due_date)->diffForHumans()); ?>

                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($loan->status === 'active'): ?> <span class="badge badge-green">Aktif</span>
                        <?php elseif($loan->status === 'overdue'): ?> <span class="badge badge-red">Terlambat</span>
                        <?php elseif($loan->status === 'returned'): ?> <span class="badge badge-gray">Kembali</span>
                        <?php else: ?> <span class="badge badge-blue">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:13px;">
                        <?php if($loan->fine > 0): ?>
                        <span style="color:var(--red); font-weight:600;">Rp <?php echo e(number_format($loan->fine, 0, ',', '.')); ?></span>
                        <?php else: ?>
                        <span style="color:#64748b;">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="<?php echo e(route('loans.show', $loan)); ?>" class="btn btn-sm btn-outline btn-icon" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
                                <?php if(in_array($loan->status, ['active', 'overdue'])): ?>
                                <form action="<?php echo e(route('loans.return', $loan)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-success btn-icon" title="Kembalikan">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if($loan->status === 'pending'): ?>
                                <form action="<?php echo e(route('loans.approve', $loan)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-sm btn-accent btn-icon" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-hand-holding-heart"></i>
                            <p>Tidak ada data peminjaman</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;"><?php echo e($loans->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/loans/index.blade.php ENDPATH**/ ?>