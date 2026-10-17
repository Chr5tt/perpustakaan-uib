<?php $__env->startSection('title', 'Detail Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Detail Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 800px; margin: 0 auto;">
    <div class="page-header">
        <div>
            <h1>Transaksi: <?php echo e($loan->loan_code); ?></h1>
            <p>Detail sirkulasi peminjaman buku</p>
        </div>
        <a href="<?php echo e(route('loans.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start;">
        <!-- SIRKULASI DETAILS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informasi Peminjaman</div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Status</span>
                    <?php if($loan->status === 'active'): ?> <span class="badge badge-green" style="font-size: 13px; padding: 4px 12px;">Aktif</span>
                    <?php elseif($loan->status === 'overdue'): ?> <span class="badge badge-red" style="font-size: 13px; padding: 4px 12px;">Terlambat</span>
                    <?php elseif($loan->status === 'returned'): ?> <span class="badge badge-gray" style="font-size: 13px; padding: 4px 12px;">Kembali</span>
                    <?php else: ?> <span class="badge badge-blue" style="font-size: 13px; padding: 4px 12px;">Pending</span>
                    <?php endif; ?>
                </div>

                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Tanggal Pinjam</span>
                    <strong style="font-size: 14.5px;"><?php echo e(\Carbon\Carbon::parse($loan->loan_date)->format('d M Y')); ?></strong>
                </div>

                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Tanggal Jatuh Tempo</span>
                    <strong style="font-size: 14.5px; color: <?php echo e($loan->status === 'overdue' ? 'var(--red)' : 'inherit'); ?>">
                        <?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?>

                    </strong>
                    <?php if(in_array($loan->status, ['active', 'overdue']) && $loan->daysLate() > 0): ?>
                    <span style="font-size: 11.5px; color: var(--red); display: block; font-weight: 600; margin-top: 2px;">
                        Terlambat <?php echo e($loan->daysLate()); ?> hari
                    </span>
                    <?php endif; ?>
                </div>

                <?php if($loan->return_date): ?>
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Tanggal Dikembalikan</span>
                    <strong style="font-size: 14.5px; color: var(--green);"><?php echo e(\Carbon\Carbon::parse($loan->return_date)->format('d M Y')); ?></strong>
                </div>
                <?php endif; ?>

                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Denda Terakumulasi</span>
                    <?php if($loan->fine > 0): ?>
                    <strong style="font-size: 18px; color: var(--red);">Rp <?php echo e(number_format($loan->fine, 0, ',', '.')); ?></strong>
                    <?php elseif($loan->status === 'active' || $loan->status === 'overdue'): ?>
                    <strong style="font-size: 15px; color: var(--red);">
                        Rp <?php echo e(number_format($loan->calculateFine(), 0, ',', '.')); ?>

                    </strong>
                    <span style="font-size: 11px; color: var(--text-muted); display: block;">(Dihitung Rp 1.000 per hari keterlambatan)</span>
                    <?php else: ?>
                    <strong style="font-size: 14.5px; color: var(--text-muted);">-</strong>
                    <?php endif; ?>
                </div>

                <?php if($loan->processedBy): ?>
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Diproses Oleh Staff</span>
                    <strong style="font-size: 14.5px;"><?php echo e($loan->processedBy->name); ?></strong>
                </div>
                <?php endif; ?>

                <?php if($loan->notes): ?>
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; display: block;">Catatan</span>
                    <p style="font-size: 13.5px; color: #475569; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid var(--border); margin-top: 4px;">
                        <?php echo e($loan->notes); ?>

                    </p>
                </div>
                <?php endif; ?>
            </div>

            <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
            <div style="margin-top: 24px; border-top: 1px solid var(--border); padding-top: 16px; display: flex; gap: 10px;">
                <?php if(in_array($loan->status, ['active', 'overdue'])): ?>
                <form action="<?php echo e(route('loans.return', $loan)); ?>" method="POST" style="flex: 1;" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-success" style="width: 100%; justify-content: center;">
                        <i class="fas fa-undo"></i> Kembalikan Buku
                    </button>
                </form>
                <?php endif; ?>

                <?php if($loan->status === 'pending'): ?>
                <form action="<?php echo e(route('loans.approve', $loan)); ?>" method="POST" style="flex: 1;">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-accent" style="width: 100%; justify-content: center;">
                        <i class="fas fa-check"></i> Setujui Peminjaman
                    </button>
                </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- BOOK & USER DETAILS -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Book Details Card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informasi Buku</div>
                </div>
                <div style="display: flex; gap: 16px;">
                    <div style="width: 80px; height: 110px; border-radius: 8px; background: linear-gradient(135deg,var(--navy) 0%,var(--navy-light) 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                        <?php if($loan->book->cover): ?>
                        <img src="<?php echo e(asset('storage/'.$loan->book->cover)); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                        <i class="fas fa-book" style="font-size: 24px; color: rgba(255,255,255,0.3);"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                            <a href="<?php echo e(route('books.show', $loan->book)); ?>" style="color: var(--navy); text-decoration: none; hover: underline;">
                                <?php echo e($loan->book->title); ?>

                            </a>
                        </h3>
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">oleh <?php echo e($loan->book->author); ?></p>
                        <p style="font-size: 12px; font-family: monospace; color: var(--text-muted);">ISBN: <?php echo e($loan->book->isbn); ?></p>
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Rak: <?php echo e($loan->book->location ?? '-'); ?></p>
                    </div>
                </div>
            </div>

            <!-- User Details Card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informasi Peminjam</div>
                </div>
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--navy); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: white; flex-shrink: 0;">
                        <?php echo e(strtoupper(substr($loan->user->name, 0, 1))); ?>

                    </div>
                    <div>
                        <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 2px;">
                            <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('members.show', $loan->user)); ?>" style="color: var(--navy); text-decoration: none;">
                                <?php echo e($loan->user->name); ?>

                            </a>
                            <?php else: ?>
                            <?php echo e($loan->user->name); ?>

                            <?php endif; ?>
                        </h3>
                        <p style="font-size: 12px; color: var(--text-muted); font-family: monospace;">NIM/NIDN: <?php echo e($loan->user->nim_nidn ?? '-'); ?></p>
                        <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 2px;">
                            <?php echo e(ucfirst($loan->user->role)); ?> <?php if($loan->user->program_studi): ?> | <?php echo e($loan->user->program_studi); ?> <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/loans/show.blade.php ENDPATH**/ ?>