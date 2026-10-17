<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun – Perpustakaan UIB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy: #1a2744;
            --navy-dark: #111c35;
            --accent: #f4a825;
            --bg: #f0f4f8;
            --border: #e2e8f0;
            --red: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
        }

        .login-left {
            width: 420px;
            background: var(--navy-dark);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(244,168,37,0.06);
            top: -100px; right: -100px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(124,58,237,0.06);
            bottom: -80px; left: -80px;
        }

        .brand-area { text-align: center; position: relative; z-index: 1; }

        .logo-box {
            width: 72px; height: 72px;
            background: var(--accent);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; font-weight: 900;
            color: var(--navy-dark);
            margin: 0 auto 20px;
        }

        .uni-name {
            font-size: 22px;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .uni-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            margin-bottom: 36px;
        }

        .feature-list { text-align: left; }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            color: rgba(255,255,255,0.7);
            font-size: 13.5px;
        }

        .feature-item i {
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            color: var(--accent);
        }

        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
            overflow-y: auto;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 500px;
            padding: 20px 0;
        }

        .login-form-wrap h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .login-form-wrap p {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 32px;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 7px;
        }
        .form-label span { color: var(--red); margin-left: 2px; }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: #1e293b;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(26,39,68,0.08);
        }

        .form-input::placeholder { color: #94a3b8; }

        select.form-input { cursor: pointer; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--navy);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 8px;
        }

        .btn-login:hover { background: #243460; }
        .btn-login:active { transform: scale(0.98); }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13.5px;
            color: #64748b;
        }

        .register-link a {
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
        }

        .register-link a:hover { text-decoration: underline; }

        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #7f1d1d;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 18px;
        }

        .alert-error ul {
            padding-left: 20px;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .login-left { display: none; }
        }
    </style>
</head>
<body>

<div class="login-left">
    <div class="brand-area">
        <div class="logo-box">UIB</div>
        <div class="uni-name">Universitas<br>Internasional Batam</div>
        <div class="uni-sub">Sistem Perpustakaan Digital</div>

        <div class="feature-list">
            <div class="feature-item">
                <i class="fas fa-book"></i>
                <span>Ribuan koleksi buku & jurnal</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-search"></i>
                <span>Pencarian katalog real-time</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-history"></i>
                <span>Riwayat peminjaman lengkap</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-calendar-check"></i>
                <span>Reservasi buku online</span>
            </div>
        </div>
    </div>
</div>

<div class="login-right">
    <div class="login-form-wrap">
        <h1>Daftar Akun Baru 📝</h1>
        <p>Lengkapi formulir di bawah untuk membuat akun</p>

        <?php if($errors->any()): ?>
        <div class="alert-error">
            <div style="font-weight:600;"><i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>Ada kesalahan:</div>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('register.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span>*</span></label>
                <input type="text" name="name" class="form-input" placeholder="Masukkan nama lengkap Anda" value="<?php echo e(old('name')); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email UIB/Pribadi <span>*</span></label>
                <input type="email" name="email" class="form-input" placeholder="email@uib.ac.id atau email@gmail.com" value="<?php echo e(old('email')); ?>" required>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">NIM / NIDN</label>
                    <input type="text" name="nim_nidn" class="form-input" placeholder="Contoh: 2112001" value="<?php echo e(old('nim_nidn')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon / HP</label>
                    <input type="text" name="phone" class="form-input" placeholder="Contoh: 0812xxxxxxxx" value="<?php echo e(old('phone')); ?>">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Program Studi</label>
                    <input type="text" name="program_studi" class="form-input" placeholder="Contoh: Sistem Informasi" value="<?php echo e(old('program_studi')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Peran (Role) <span>*</span></label>
                    <select name="role" class="form-input" required>
                        <option value="mahasiswa" <?php echo e(old('role') === 'mahasiswa' ? 'selected' : ''); ?>>Mahasiswa</option>
                        <option value="dosen" <?php echo e(old('role') === 'dosen' ? 'selected' : ''); ?>>Dosen</option>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Password <span>*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span>*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-user-plus" style="margin-right:6px;"></i> Daftar Akun
            </button>
        </form>

        <div class="register-link">
            Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk Disini</a>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/auth/register.blade.php ENDPATH**/ ?>