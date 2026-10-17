<?php $__env->startSection('title', 'Detail Anggota'); ?>
<?php $__env->startSection('page-title', 'Detail Anggota'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 20px; align-items: start;">
    <!-- MEMBER PROFILE CARD -->
    <div class="card">
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 10px 0;">
            <div style="width: 90px; height: 90px; border-radius: 50%; background: var(--navy); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; color: white; margin-bottom: 16px;">
                <?php echo e(strtoupper(substr($member->name, 0, 1))); ?>

            </div>
            
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 4px;"><?php echo e($member->name); ?></h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;"><?php echo e($member->email); ?></p>
            
            <div style="display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; margin-bottom: 24px;">
                <?php if($member->role === 'admin'): ?> <span class="badge badge-purple">Admin</span>
                <?php elseif($member->role === 'staff'): ?> <span class="badge badge-blue">Staff</span>
                <?php elseif($member->role === 'dosen'): ?> <span class="badge badge-accent">Dosen</span>
                <?php else: ?> <span class="badge badge-gray">Mahasiswa</span>
                <?php endif; ?>
                
                <?php if($member->program_studi): ?>
                <span class="badge badge-gray"><?php echo e($member->program_studi); ?></span>
                <?php endif; ?>
            </div>
            
            <div style="width: 100%; border-top: 1px solid var(--border); padding-top: 16px; text-align: left;">
                <div style="margin-bottom: 12px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">NIM / NIDN</div>
                    <div style="font-size: 13.5px; font-weight: 600; font-family: monospace;"><?php echo e($member->nim_nidn ?? '-'); ?></div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">No. Telepon / HP</div>
                    <div style="font-size: 13.5px; font-weight: 600;"><?php echo e($member->phone ?? '-'); ?></div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Status Peminjaman Aktif</div>
                    <div style="font-size: 13.5px; font-weight: 600;">
                        <?php echo e($member->loans()->whereIn('status', ['active', 'overdue'])->count()); ?> buku dipinjam
                    </div>
                </div>
            </div>
            
            <div style="width: 100%; display: flex; flex-direction: column; gap: 8px; margin-top: 16px;">
                <a href="<?php echo e(route('members.edit', $member)); ?>" class="btn btn-outline" style="justify-content: center;">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
                <a href="<?php echo e(route('members.index')); ?>" class="btn btn-outline" style="justify-content: center;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- MEMBER SIRKULASI / RIWAYAT LOANS -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Riwayat Sirkulasi Peminjaman</div>
                <div class="card-subtitle">Menampilkan riwayat peminjaman buku anggota ini</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali / Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $member->loans()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-family: monospace; font-size: 12px; font-weight: 600; color: var(--navy);">
                            <?php echo e($loan->loan_code); ?>

                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;"><?php echo e(Str::limit($loan->book->title, 40)); ?></div>
                            <div style="font-size: 11.5px; color: var(--text-muted);"><?php echo e($loan->book->author); ?></div>
                        </td>
                        <td style="font-size: 13px;"><?php echo e(\Carbon\Carbon::parse($loan->loan_date)->format('d M Y')); ?></td>
                        <td style="font-size: 13px;">
                            <?php if($loan->status === 'returned'): ?>
                                <?php echo e(\Carbon\Carbon::parse($loan->return_date)->format('d M Y')); ?>

                            <?php else: ?>
                                <?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?>

                                <?php if($loan->status === 'overdue'): ?>
                                <span style="display: block; font-size: 11px; color: var(--red); font-weight: 600;">Terlambat</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($loan->status === 'active'): ?> <span class="badge badge-green">Aktif</span>
                            <?php elseif($loan->status === 'overdue'): ?> <span class="badge badge-red">Terlambat</span>
                            <?php elseif($loan->status === 'returned'): ?> <span class="badge badge-gray">Kembali</span>
                            <?php else: ?> <span class="badge badge-blue">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 13px;">
                            <?php if($loan->fine > 0): ?>
                            <span style="color: var(--red); font-weight: 600;">Rp <?php echo e(number_format($loan->fine, 0, ',', '.')); ?></span>
                            <?php else: ?> -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                            Anggota ini belum pernah meminjam buku.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/members/show.blade.php ENDPATH**/ ?>