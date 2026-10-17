<?php $__env->startSection('title', $book->title); ?>
<?php $__env->startSection('page-title', 'Detail Buku'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start;">

    <!-- MAIN INFO -->
    <div>
        <div class="card">
            <div style="display:flex; gap:24px; align-items:flex-start;">
                <!-- Cover -->
                <div style="width:130px; height:180px; border-radius:10px; background:linear-gradient(135deg,var(--navy) 0%,var(--navy-light) 100%); display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden;">
                    <?php if($book->cover): ?>
                    <img src="<?php echo e(asset('storage/'.$book->cover)); ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                    <i class="fas fa-book" style="font-size:40px; color:rgba(255,255,255,0.3);"></i>
                    <?php endif; ?>
                </div>

                <div style="flex:1;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                        <span class="badge badge-blue"><?php echo e($book->category->name); ?></span>
                        <?php if($book->available > 0): ?>
                        <span class="badge badge-green"><i class="fas fa-check-circle" style="margin-right:3px;"></i>Tersedia (<?php echo e($book->available); ?>)</span>
                        <?php else: ?>
                        <span class="badge badge-red"><i class="fas fa-times-circle" style="margin-right:3px;"></i>Tidak Tersedia</span>
                        <?php endif; ?>
                    </div>

                    <h1 style="font-size:22px; font-weight:800; margin-bottom:6px;"><?php echo e($book->title); ?></h1>
                    <p style="font-size:14px; color:var(--text-muted); margin-bottom:16px;">oleh <strong><?php echo e($book->author); ?></strong></p>

                    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:16px;">
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Penerbit</div>
                            <div style="font-size:13px; font-weight:600;"><?php echo e($book->publisher); ?></div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Tahun</div>
                            <div style="font-size:13px; font-weight:600;"><?php echo e($book->year); ?></div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">ISBN</div>
                            <div style="font-size:13px; font-weight:600; font-family:monospace;"><?php echo e($book->isbn); ?></div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Total Stok</div>
                            <div style="font-size:13px; font-weight:600;"><?php echo e($book->stock); ?> eksemplar</div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Lokasi Rak</div>
                            <div style="font-size:13px; font-weight:600;"><?php echo e($book->location ?? '-'); ?></div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Dipinjam</div>
                            <div style="font-size:13px; font-weight:600;"><?php echo e($book->stock - $book->available); ?>x</div>
                        </div>
                    </div>

                    <?php if($book->description): ?>
                    <p style="font-size:13.5px; color:#475569; line-height:1.7;"><?php echo e($book->description); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if(auth()->user()->isAdmin()): ?>
        <!-- LOAN HISTORY -->
        <div class="card" style="margin-top:20px;">
            <div class="card-header">
                <div class="card-title">Riwayat Peminjaman</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $book->loans()->latest()->take(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="font-family:monospace; font-size:12px;"><?php echo e($loan->loan_code); ?></td>
                            <td><?php echo e($loan->user->name); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($loan->loan_date)->format('d M Y')); ?></td>
                            <td><?php echo e($loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d M Y') : '-'); ?></td>
                            <td>
                                <?php if($loan->status === 'returned'): ?> <span class="badge badge-gray">Dikembalikan</span>
                                <?php elseif($loan->status === 'active'): ?> <span class="badge badge-green">Aktif</span>
                                <?php elseif($loan->status === 'overdue'): ?> <span class="badge badge-red">Terlambat</span>
                                <?php else: ?> <span class="badge badge-blue">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" style="text-align:center; padding:16px; color:#64748b; font-size:13px;">Belum pernah dipinjam</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- SIDEBAR ACTIONS -->
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Aksi</div>
            <?php if($book->available > 0): ?>
                <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
                <a href="<?php echo e(route('loans.create', ['book_id' => $book->id])); ?>" class="btn btn-accent" style="width:100%; justify-content:center; margin-bottom:10px;">
                    <i class="fas fa-hand-holding"></i> Pinjamkan Buku
                </a>
                <?php else: ?>
                <form action="<?php echo e(route('loans.request')); ?>" method="POST" style="margin-bottom:10px;"
                      onsubmit="return confirm('Ajukan peminjaman buku ini? Menunggu persetujuan petugas.')">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="book_id" value="<?php echo e($book->id); ?>">
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="fas fa-hand-holding"></i> Ajukan Peminjaman
                    </button>
                </form>
                <?php endif; ?>
            <?php else: ?>
                <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
                <div style="font-size: 13px; color: var(--red); font-weight: 600; text-align: center; margin-bottom: 10px;">
                    Buku habis (sedang dipinjam)
                </div>
                <?php else: ?>
                <form action="<?php echo e(route('reservations.store')); ?>" method="POST" style="margin-bottom:10px;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="book_id" value="<?php echo e($book->id); ?>">
                    <button type="submit" class="btn btn-outline" style="width:100%; justify-content:center;">
                        <i class="fas fa-clock"></i> Tambah Antrian
                    </button>
                </form>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
            <a href="<?php echo e(route('books.edit', $book)); ?>" class="btn btn-outline" style="width:100%; justify-content:center; margin-bottom:10px;">
                <i class="fas fa-edit"></i> Edit Buku
            </a>
            <form action="<?php echo e(route('books.destroy', $book)); ?>" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger" style="width:100%; justify-content:center;">
                    <i class="fas fa-trash"></i> Hapus Buku
                </button>
            </form>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:12px;">Statistik</div>
            <div style="display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:#64748b;">Total Pinjam</span>
                    <strong><?php echo e($book->loans()->count()); ?>x</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:#64748b;">Sedang Dipinjam</span>
                    <strong><?php echo e($book->loans()->whereIn('status',['active','overdue'])->count()); ?>x</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:#64748b;">Reservasi Aktif</span>
                    <strong><?php echo e($book->reservations()->whereIn('status',['waiting','ready'])->count()); ?></strong>
                </div>
            </div>
        </div>

        <a href="<?php echo e(route('books.index')); ?>" class="btn btn-outline" style="justify-content:center;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/books/show.blade.php ENDPATH**/ ?>