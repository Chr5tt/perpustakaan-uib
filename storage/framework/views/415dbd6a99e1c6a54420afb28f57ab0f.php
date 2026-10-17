<?php $__env->startSection('title', 'Anggota'); ?>
<?php $__env->startSection('page-title', 'Anggota'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Data Anggota</h1>
        <p><?php echo e($members->total()); ?> anggota terdaftar</p>
    </div>
    <a href="<?php echo e(route('members.create')); ?>" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Tambah Anggota
    </a>
</div>

<div class="card">
    <form action="<?php echo e(route('members.index')); ?>" method="GET" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
        <div class="search-box" style="flex:1; min-width:200px;">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari nama, email, NIM/NIDN..." value="<?php echo e(request('search')); ?>">
        </div>
        <select name="role" class="form-control" style="width:160px;">
            <option value="">Semua Role</option>
            <option value="mahasiswa" <?php echo e(request('role') === 'mahasiswa' ? 'selected' : ''); ?>>Mahasiswa</option>
            <option value="dosen" <?php echo e(request('role') === 'dosen' ? 'selected' : ''); ?>>Dosen</option>
            <option value="staff" <?php echo e(request('role') === 'staff' ? 'selected' : ''); ?>>Staff</option>
            <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
        <?php if(request()->hasAny(['search','role'])): ?><a href="<?php echo e(route('members.index')); ?>" class="btn btn-outline">Reset</a><?php endif; ?>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>NIM/NIDN</th>
                    <th>Program Studi</th>
                    <th>No. HP</th>
                    <th>Role</th>
                    <th>Dipinjam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:34px; height:34px; border-radius:50%; background:var(--navy); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; color:white; flex-shrink:0;">
                                <?php echo e(strtoupper(substr($member->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div style="font-weight:600; font-size:13px;"><?php echo e($member->name); ?></div>
                                <div style="font-size:11.5px; color:#64748b;"><?php echo e($member->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace; font-size:13px;"><?php echo e($member->nim_nidn ?? '-'); ?></td>
                    <td style="font-size:13px;"><?php echo e($member->program_studi ?? '-'); ?></td>
                    <td style="font-size:13px;"><?php echo e($member->phone ?? '-'); ?></td>
                    <td>
                        <?php if($member->role === 'admin'): ?> <span class="badge badge-purple">Admin</span>
                        <?php elseif($member->role === 'staff'): ?> <span class="badge badge-blue">Staff</span>
                        <?php elseif($member->role === 'dosen'): ?> <span class="badge badge-accent">Dosen</span>
                        <?php else: ?> <span class="badge badge-gray">Mahasiswa</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="font-weight:600; font-size:13px;"><?php echo e($member->loans()->whereIn('status',['active','overdue'])->count()); ?></span>
                        <span style="font-size:11.5px; color:#64748b;"> aktif</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="<?php echo e(route('members.show', $member)); ?>" class="btn btn-sm btn-outline btn-icon" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('members.edit', $member)); ?>" class="btn btn-sm btn-outline btn-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if((int) $member->id !== (int) auth()->id()): ?>
                            <form action="<?php echo e(route('members.destroy', $member)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus anggota ini?')">
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
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Tidak ada anggota ditemukan</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;"><?php echo e($members->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/members/index.blade.php ENDPATH**/ ?>