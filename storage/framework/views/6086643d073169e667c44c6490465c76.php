<?php $__env->startSection('title', 'Pinjam Buku'); ?>
<?php $__env->startSection('page-title', 'Pinjam Buku'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:600px;">
    <div class="page-header">
        <div>
            <h1>Form Peminjaman</h1>
            <p>Isi data peminjaman buku</p>
        </div>
        <a href="<?php echo e(route('loans.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card">
        <form action="<?php echo e(route('loans.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label">Anggota <span>*</span></label>
                <select name="user_id" class="form-control" required>
                    <option value="">Pilih Anggota</option>
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($member->id); ?>" <?php echo e(old('user_id', request('user_id')) == $member->id ? 'selected' : ''); ?>>
                        <?php echo e($member->name); ?> — <?php echo e($member->nim_nidn); ?> (<?php echo e(ucfirst($member->role)); ?>)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Buku <span>*</span></label>
                <select name="book_id" class="form-control" required>
                    <option value="">Pilih Buku</option>
                    <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($book->id); ?>" <?php echo e(old('book_id', request('book_id')) == $book->id ? 'selected' : ''); ?>

                        <?php echo e($book->available <= 0 ? 'disabled' : ''); ?>>
                        <?php echo e($book->title); ?> — <?php echo e($book->author); ?>

                        (Tersedia: <?php echo e($book->available); ?>)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal Pinjam <span>*</span></label>
                    <input type="date" name="loan_date" class="form-control" value="<?php echo e(old('loan_date', date('Y-m-d'))); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Jatuh Tempo <span>*</span></label>
                    <input type="date" name="due_date" class="form-control" value="<?php echo e(old('due_date', date('Y-m-d', strtotime('+14 days')))); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan opsional..."><?php echo e(old('notes')); ?></textarea>
            </div>

            <?php if($errors->any()): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($e); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <a href="<?php echo e(route('loans.index')); ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-accent">
                    <i class="fas fa-hand-holding"></i> Pinjamkan Buku
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/loans/create.blade.php ENDPATH**/ ?>