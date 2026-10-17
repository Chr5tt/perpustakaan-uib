<?php $__env->startSection('title', 'Reservasi Buku'); ?>
<?php $__env->startSection('page-title', 'Reservasi Buku'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Daftar Reservasi</h1>
        <p>Kelola antrean reservasi peminjaman buku</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Antrean Reservasi</div>
            <div class="card-subtitle">Menampilkan reservasi aktif dan riwayat antrean</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
                    <th>Peminjam</th>
                    <?php endif; ?>
                    <th>Tgl Reservasi</th>
                    <th>Berlaku Sampai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e(($reservations->currentPage() - 1) * $reservations->perPage() + $i + 1); ?></td>
                    <td>
                        <div style="font-weight: 600; font-size: 13.5px;"><?php echo e($res->book->title); ?></div>
                        <div style="font-size: 11.5px; color: var(--text-muted);"><?php echo e($res->book->author); ?></div>
                    </td>
                    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
                    <td>
                        <div style="font-size: 13px; font-weight: 500;"><?php echo e($res->user->name); ?></div>
                        <div style="font-size: 11.5px; color: var(--text-muted);"><?php echo e($res->user->nim_nidn); ?> (<?php echo e(ucfirst($res->user->role)); ?>)</div>
                    </td>
                    <?php endif; ?>
                    <td style="font-size: 13px;"><?php echo e(\Carbon\Carbon::parse($res->reservation_date)->format('d M Y')); ?></td>
                    <td style="font-size: 13px;"><?php echo e(\Carbon\Carbon::parse($res->expiry_date)->format('d M Y')); ?></td>
                    <td>
                        <?php if($res->status === 'waiting'): ?> <span class="badge badge-blue">Menunggu Antrean</span>
                        <?php elseif($res->status === 'ready'): ?> <span class="badge badge-green">Siap Diambil</span>
                        <?php elseif($res->status === 'completed'): ?> <span class="badge badge-gray">Selesai</span>
                        <?php else: ?> <span class="badge badge-red">Dibatalkan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(in_array($res->status, ['waiting', 'ready'])): ?>
                            <?php if(auth()->id() === $res->user_id || auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
                            <form action="<?php echo e(route('reservations.destroy', $res)); ?>" method="POST" style="display: inline;"
                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Batalkan Reservasi">
                                    <i class="fas fa-times"></i> Batalkan
                                </button>
                            </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 12px;">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);">
                        Tidak ada antrean reservasi aktif.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        <?php echo e($reservations->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/reservations/index.blade.php ENDPATH**/ ?>