<?php $__env->startSection('title', 'Profil'); ?>
<?php $__env->startSection('page-title', 'Profil'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:900px;">

    <!-- PROFILE CARD -->
    <div class="card" style="margin-bottom:20px;">
        <div style="display:flex; gap:24px; align-items:center;">
            <div style="width:80px; height:80px; border-radius:50%; background:var(--navy); display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:800; color:white; flex-shrink:0;">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

            </div>
            <div>
                <h2 style="font-size:20px; font-weight:800; margin-bottom:4px;"><?php echo e(auth()->user()->name); ?></h2>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <span class="badge badge-purple"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                    <?php if(auth()->user()->program_studi): ?>
                    <span class="badge badge-gray"><?php echo e(auth()->user()->program_studi); ?></span>
                    <?php endif; ?>
                    <?php if(auth()->user()->nim_nidn): ?>
                    <span class="badge badge-blue"><?php echo e(auth()->user()->nim_nidn); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
        <!-- EDIT PROFILE -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Data Diri</div>
            </div>
            <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', auth()->user()->name)); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', auth()->user()->email)); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIM / NIDN</label>
                    <input type="text" class="form-control" value="<?php echo e(auth()->user()->nim_nidn); ?>" disabled style="background:#f8fafc;">
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', auth()->user()->phone)); ?>">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </form>
        </div>

        <!-- CHANGE PASSWORD -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ganti Password</div>
            </div>
            <form action="<?php echo e(route('profile.password')); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="form-group">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-outline" style="width:100%; justify-content:center;">
                    <i class="fas fa-lock"></i> Ganti Password
                </button>
            </form>
        </div>
    </div>

    <!-- DATA PEMINJAMAN -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Riwayat Peminjaman Saya</div>
                <div class="card-subtitle">Semua transaksi peminjaman Anda</div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('seminar-tab', this)">Data Peminjaman</button>
            <button class="tab-btn" onclick="showTab('sertifikasi-tab', this)">Reservasi</button>
        </div>

        <div id="seminar-tab">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $myLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td>
                                <div style="font-weight:600;"><?php echo e($loan->book->title); ?></div>
                                <div style="font-size:11.5px; color:#64748b;"><?php echo e($loan->loan_code); ?></div>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($loan->loan_date)->format('d M Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($loan->due_date)->format('d M Y')); ?></td>
                            <td>
                                <?php if($loan->status === 'active'): ?> <span class="badge badge-green">Aktif</span>
                                <?php elseif($loan->status === 'overdue'): ?> <span class="badge badge-red">Terlambat</span>
                                <?php elseif($loan->status === 'returned'): ?> <span class="badge badge-gray">Dikembalikan</span>
                                <?php else: ?> <span class="badge badge-blue">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($loan->fine > 0): ?>
                                <span style="color:var(--red); font-weight:600;">Rp <?php echo e(number_format($loan->fine, 0, ',', '.')); ?></span>
                                <?php else: ?> -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" style="text-align:center; padding:24px; color:#64748b;">Belum ada riwayat peminjaman</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="sertifikasi-tab" style="display:none;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Buku</th>
                            <th>Tgl Reservasi</th>
                            <th>Berlaku Sampai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $myReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td style="font-weight:600;"><?php echo e($res->book->title); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($res->reservation_date)->format('d M Y')); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($res->expiry_date)->format('d M Y')); ?></td>
                            <td>
                                <?php if($res->status === 'waiting'): ?> <span class="badge badge-blue">Menunggu</span>
                                <?php elseif($res->status === 'ready'): ?> <span class="badge badge-green">Siap Diambil</span>
                                <?php elseif($res->status === 'completed'): ?> <span class="badge badge-gray">Selesai</span>
                                <?php else: ?> <span class="badge badge-red">Dibatalkan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" style="text-align:center; padding:24px; color:#64748b;">Belum ada reservasi</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showTab(tabId, btn) {
    document.querySelectorAll('.tabs .tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('seminar-tab').style.display = 'none';
    document.getElementById('sertifikasi-tab').style.display = 'none';
    document.getElementById(tabId).style.display = 'block';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/profile/index.blade.php ENDPATH**/ ?>