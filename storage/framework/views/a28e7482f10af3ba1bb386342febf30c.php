<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Perpustakaan UIB'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy: #1a2744;
            --navy-dark: #111c35;
            --navy-light: #243460;
            --accent: #f4a825;
            --accent-light: #fbbf24;
            --purple: #7c3aed;
            --purple-light: #8b5cf6;
            --green: #10b981;
            --red: #ef4444;
            --blue: #3b82f6;
            --bg: #f0f4f8;
            --sidebar-w: 260px;
            --white: #ffffff;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --card-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy-dark);
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-logo {
            width: 44px; height: 44px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 16px;
            color: var(--navy-dark);
            flex-shrink: 0;
        }

        .brand-text { line-height: 1.2; }
        .brand-text .uni { font-size: 9px; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.5px; }
        .brand-text .name { font-size: 12px; font-weight: 700; color: white; }

        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; }

        .nav-section-label {
            font-size: 9px;
            font-weight: 700;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 20px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.2s;
            position: relative;
            margin: 1px 0;
        }

        .nav-item:hover {
            color: white;
            background: rgba(255,255,255,0.07);
        }

        .nav-item.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }

        .nav-item i { width: 18px; text-align: center; font-size: 14px; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-card:hover { background: rgba(255,255,255,0.1); }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
            color: var(--navy-dark);
            flex-shrink: 0;
        }

        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 12.5px; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 10.5px; color: rgba(255,255,255,0.45); text-transform: capitalize; }

        /* ===== MAIN LAYOUT ===== */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .topbar {
            background: var(--navy);
            padding: 0 28px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .topbar-left { display: flex; align-items: center; gap: 16px; }

        .menu-toggle {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: rgba(255,255,255,0.08);
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 16px;
            transition: background 0.2s;
        }

        .menu-toggle:hover { background: rgba(255,255,255,0.14); }

        .page-title {
            font-size: 16px;
            font-weight: 700;
            color: white;
        }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.75);
            font-size: 15px;
            transition: all 0.2s;
            position: relative;
        }

        .topbar-btn:hover { background: rgba(255,255,255,0.15); color: white; }

        .badge-dot {
            width: 8px; height: 8px;
            background: var(--red);
            border-radius: 50%;
            position: absolute;
            top: 6px; right: 6px;
            border: 2px solid var(--navy);
        }

        /* ===== CONTENT ===== */
        .content {
            padding: 28px;
            flex: 1;
        }

        /* ===== CARDS ===== */
        .card {
            background: white;
            border-radius: 14px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }

        .card-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ===== STAT CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.navy { background: rgba(26,39,68,0.1); color: var(--navy); }
        .stat-icon.accent { background: rgba(244,168,37,0.12); color: var(--accent); }
        .stat-icon.green { background: rgba(16,185,129,0.1); color: var(--green); }
        .stat-icon.red { background: rgba(239,68,68,0.1); color: var(--red); }
        .stat-icon.purple { background: rgba(124,58,237,0.1); color: var(--purple); }

        .stat-info { flex: 1; }
        .stat-value { font-size: 24px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 4px; font-weight: 500; }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }

        thead tr { border-bottom: 2px solid var(--border); }
        thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }

        tbody td {
            padding: 13px 14px;
            font-size: 13.5px;
            vertical-align: middle;
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-green { background: rgba(16,185,129,0.1); color: var(--green); }
        .badge-red { background: rgba(239,68,68,0.1); color: var(--red); }
        .badge-accent { background: rgba(244,168,37,0.12); color: #b45309; }
        .badge-blue { background: rgba(59,130,246,0.1); color: var(--blue); }
        .badge-purple { background: rgba(124,58,237,0.1); color: var(--purple); }
        .badge-gray { background: #f1f5f9; color: var(--text-muted); }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 9px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--navy);
            color: white;
        }

        .btn-primary:hover { background: var(--navy-light); }

        .btn-accent {
            background: var(--accent);
            color: var(--navy-dark);
        }

        .btn-accent:hover { background: var(--accent-light); }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text);
        }

        .btn-outline:hover { border-color: var(--navy); color: var(--navy); background: rgba(26,39,68,0.04); }

        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 7px; }
        .btn-icon { padding: 8px; border-radius: 8px; }

        .btn-danger { background: rgba(239,68,68,0.1); color: var(--red); }
        .btn-danger:hover { background: var(--red); color: white; }

        .btn-success { background: rgba(16,185,129,0.1); color: var(--green); }
        .btn-success:hover { background: var(--green); color: white; }

        /* ===== FORMS ===== */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-label span { color: var(--red); margin-left: 2px; }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            transition: border-color 0.2s, box-shadow 0.2s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(26,39,68,0.08);
        }

        .form-control::placeholder { color: #94a3b8; }

        select.form-control { cursor: pointer; }

        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }

        /* ===== ALERTS ===== */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .alert-success { background: rgba(16,185,129,0.08); color: #065f46; border: 1px solid rgba(16,185,129,0.2); }
        .alert-error { background: rgba(239,68,68,0.08); color: #7f1d1d; border: 1px solid rgba(239,68,68,0.2); }
        .alert-info { background: rgba(59,130,246,0.08); color: #1e3a5f; border: 1px solid rgba(59,130,246,0.2); }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h1 { font-size: 22px; font-weight: 800; color: var(--text); }
        .page-header p { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        /* ===== SEARCH + FILTER BAR ===== */
        .toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 13px;
        }

        .search-box input {
            width: 100%;
            padding: 9px 14px 9px 36px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--text);
            transition: border-color 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--navy);
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: center;
            margin-top: 20px;
        }

        .page-btn {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            background: white;
            font-size: 13px;
            font-family: inherit;
            font-weight: 600;
            color: var(--text);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .page-btn:hover { border-color: var(--navy); color: var(--navy); }
        .page-btn.active { background: var(--navy); border-color: var(--navy); color: white; }
        .page-btn.disabled { opacity: 0.4; cursor: not-allowed; }

        /* ===== BOOK GRID ===== */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 16px;
        }

        .book-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: var(--card-shadow);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .book-cover {
            width: 100%;
            height: 160px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .book-cover img { width: 100%; height: 100%; object-fit: cover; }
        .book-cover-placeholder { font-size: 40px; opacity: 0.3; color: white; }

        .book-cover-badge {
            position: absolute;
            top: 8px; right: 8px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .book-info { padding: 14px; }
        .book-title { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 4px; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .book-author { font-size: 11.5px; color: var(--text-muted); }
        .book-category { display: inline-block; margin-top: 8px; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: rgba(26,39,68,0.07); color: var(--navy); }

        /* ===== MODAL ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.show { display: flex; }

        .modal {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 28px;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .modal-title { font-size: 18px; font-weight: 800; color: var(--text); }

        .modal-close {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: #f1f5f9;
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            color: var(--text-muted);
            transition: all 0.2s;
        }

        .modal-close:hover { background: var(--red); color: white; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .form-grid { grid-template-columns: 1fr; }
        }

        /* ===== DIVIDER ACCENT ===== */
        .accent-line {
            height: 3px;
            background: linear-gradient(to right, var(--accent), var(--purple));
            border-radius: 2px;
            margin-bottom: 16px;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-muted);
        }

        .empty-state i { font-size: 48px; opacity: 0.2; margin-bottom: 12px; display: block; }
        .empty-state p { font-size: 14px; }

        /* ===== TABS ===== */
        .tabs { display: flex; gap: 4px; margin-bottom: 20px; border-bottom: 2px solid var(--border); }

        .tab-btn {
            padding: 10px 18px;
            border: none;
            background: none;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            position: relative;
            transition: color 0.2s;
            margin-bottom: -2px;
        }

        .tab-btn.active {
            color: var(--navy);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: var(--navy);
            border-radius: 2px 2px 0 0;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">UIB</div>
        <div class="brand-text">
            <div class="uni">Universitas Internasional</div>
            <div class="name">Batam</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-th-large"></i> Beranda
        </a>
        <a href="<?php echo e(route('books.index')); ?>" class="nav-item <?php echo e(request()->routeIs('books.*') ? 'active' : ''); ?>">
            <i class="fas fa-book"></i> Koleksi Buku
        </a>
        <?php if(auth()->user()->isAdmin()): ?>
        <a href="<?php echo e(route('loans.index')); ?>" class="nav-item <?php echo e(request()->routeIs('loans.*') ? 'active' : ''); ?>">
            <i class="fas fa-hand-holding-heart"></i> Peminjaman
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('reservations.index')); ?>" class="nav-item <?php echo e(request()->routeIs('reservations.*') ? 'active' : ''); ?>">
            <i class="fas fa-calendar-check"></i> Reservasi
        </a>

        <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff'): ?>
        <div class="nav-section-label" style="margin-top:8px;">Administrasi</div>
        <a href="<?php echo e(route('members.index')); ?>" class="nav-item <?php echo e(request()->routeIs('members.*') ? 'active' : ''); ?>">
            <i class="fas fa-users"></i> Anggota
        </a>
        <a href="<?php echo e(route('categories.index')); ?>" class="nav-item <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">
            <i class="fas fa-tags"></i> Kategori
        </a>
        <?php endif; ?>

        <div class="nav-section-label" style="margin-top:8px;">Akun</div>
        <a href="<?php echo e(route('profile.index')); ?>" class="nav-item <?php echo e(request()->routeIs('profile.*') ? 'active' : ''); ?>">
            <i class="fas fa-user-circle"></i> Profil
        </a>
        <a href="<?php echo e(route('logout')); ?>" class="nav-item"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;"><?php echo csrf_field(); ?></form>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
            <div class="user-info">
                <div class="user-name"><?php echo e(auth()->user()->name); ?></div>
                <div class="user-role"><?php echo e(ucfirst(auth()->user()->role)); ?></div>
            </div>
            <i class="fas fa-ellipsis-h" style="color:rgba(255,255,255,0.3); font-size:12px;"></i>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main-wrap">
    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="fas fa-bars"></i>
            </button>
            <span class="page-title"><?php echo $__env->yieldContent('page-title', 'Perpustakaan UIB'); ?></span>
        </div>
        <div class="topbar-right">
            <button class="topbar-btn" title="Notifikasi">
                <i class="fas fa-bell"></i>
                <span class="badge-dot"></span>
            </button>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="content">
        <?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<script>
// Auto-close alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 4000);
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/layouts/app.blade.php ENDPATH**/ ?>