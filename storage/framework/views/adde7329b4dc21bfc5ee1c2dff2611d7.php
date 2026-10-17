<?php $__env->startSection('title', 'Dashboard – Perpustakaan UIB'); ?>
<?php $__env->startSection('page-title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, <?php echo e(auth()->user()->name); ?>! Berikut ringkasan sistem perpustakaan.</p>
    </div>
    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
    <a href="<?php echo e(route('loans.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Peminjaman Baru
    </a>
    <?php endif; ?>
</div>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon navy"><i class="fas fa-book"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['total_books']); ?></div>
            <div class="stat-label">Total Koleksi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-book-open"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['available_books']); ?></div>
            <div class="stat-label">Tersedia</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon accent"><i class="fas fa-hand-holding"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['active_loans']); ?></div>
            <div class="stat-label">Dipinjam</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['overdue_loans']); ?></div>
            <div class="stat-label">Terlambat</div>
        </div>
    </div>
</div>

<?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
<div class="stats-grid" style="grid-template-columns: repeat(3,1fr); margin-top: -8px;">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['total_members']); ?></div>
            <div class="stat-label">Total Anggota</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['pending_reservations']); ?></div>
            <div class="stat-label">Reservasi Menunggu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon accent"><i class="fas fa-money-bill"></i></div>
        <div class="stat-info">
            <div class="stat-value">Rp <?php echo e(number_format($stats['total_fines'], 0, ',', '.')); ?></div>
            <div class="stat-label">Total Denda</div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if(!auth()->user()->isAdmin()): ?>
<div class="stats-grid" style="grid-template-columns: repeat(2,1fr); margin-top: -8px;">
    <div class="stat-card">
        <div class="stat-icon accent"><i class="fas fa-hand-holding"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['my_active_loans']); ?></div>
            <div class="stat-label">Pinjaman Aktif Saya</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo e($stats['my_reservations']); ?></div>
            <div class="stat-label">Reservasi Saya</div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- CONTENT ROW -->
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; margin-top: 8px;">

    <!-- RECENT LOANS -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Peminjaman Terbaru</div>
                <div class="card-subtitle">Daftar transaksi peminjaman aktif</div>
            </div>
            <?php if(auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('loans.index')); ?>" class="btn btn-outline btn-sm">Lihat Semua</a>
            <?php endif; ?>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Buku</th>
                        <?php if(auth()->user()->isAdmin()): ?><th>Peminjam</th><?php endif; ?>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="font-weight:600; font-size:13px;"><?php echo e(Str::limit($loan->book->title, 30)); ?></div>
                            <div style="font-size:11.5px; color:#64748b;"><?php echo e($loan->loan_code); ?></div>
                        </td>
                        <?php if(auth()->user()->isAdmin()): ?>
                        <td>
                            <div style="font-size:13px; font-weight:500;"><?php echo e(Str::limit($loan->user->name, 20)); ?></div>
                            <div style="font-size:11.5px; color:#64748b;"><?php echo e(ucfirst($loan->user->role)); ?></div>
                        </td>
                        <?php endif; ?>
                        <td style="font-size:13px;"><?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?></td>
                        <td>
                            <?php if($loan->status === 'active'): ?>
                                <span class="badge badge-green">Aktif</span>
                            <?php elseif($loan->status === 'overdue'): ?>
                                <span class="badge badge-red">Terlambat</span>
                            <?php elseif($loan->status === 'returned'): ?>
                                <span class="badge badge-gray">Dikembalikan</span>
                            <?php else: ?>
                                <span class="badge badge-blue">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" style="text-align:center; padding:24px; color:#64748b; font-size:13px;">Belum ada data peminjaman</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- POPULAR BOOKS -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Buku Populer</div>
                <div class="card-subtitle">Paling sering dipinjam</div>
            </div>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px;">
            <?php $__empty_1 = true; $__currentLoopData = $popularBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:10px; background:#f8fafc; border:1px solid #f1f5f9;">
                <div style="width:32px; height:32px; border-radius:8px; background:var(--navy); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800; color:white; flex-shrink:0;">
                    <?php echo e($i + 1); ?>

                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?php echo e($book->title); ?></div>
                    <div style="font-size:11.5px; color:#64748b;"><?php echo e($book->loans_count); ?> kali dipinjam</div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="font-size:13px; color:#64748b; text-align:center; padding:20px;">Belum ada data</p>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/dashboard/index.blade.php ENDPATH**/ ?>