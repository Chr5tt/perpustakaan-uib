<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan UIB - Universitas Internasional Batam</title>
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --accent-color: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            line-height: 1.6;
        }

        /* Navbar Styling */
        nav {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-text h1 {
            font-size: 1.4rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .logo-text p {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--secondary-color);
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white !important;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            font-weight: 600 !important;
            transition: background-color 0.3s, transform 0.2s !important;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.95), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=2070') no-repeat center center/cover;
            color: white;
            padding: 8rem 5% 6rem 5%;
            text-align: center;
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero h2 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            max-width: 800px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            color: #94a3b8;
            margin-bottom: 2.5rem;
            max-width: 600px;
        }

        .search-bar {
            background: white;
            padding: 0.5rem;
            border-radius: 50px;
            display: flex;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .search-bar input {
            flex: 1;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            outline: none;
        }

        .search-bar button {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #d97706;
        }

        /* Features Section */
        .features {
            padding: 5rem 5%;
            background-color: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h3 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: #64748b;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background-color: var(--light-color);
            padding: 2.5rem 2rem;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e2e8f0;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
            display: inline-block;
        }

        .feature-card h4 {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            color: var(--dark-color);
        }

        .feature-card p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Info Section */
        .info-stats {
            background-color: var(--primary-color);
            color: white;
            padding: 4rem 5%;
            text-align: center;
        }

        .stats-grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-item h5 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .stat-item p {
            color: #cbd5e1;
            font-size: 1rem;
            margin-top: 0.25rem;
        }

        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: #94a3b8;
            padding: 3rem 5% 1.5rem 5%;
            font-size: 0.9rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            border-bottom: 1px solid #334155;
            padding-bottom: 2rem;
            margin-bottom: 1.5rem;
        }

        .footer-brand h4 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .footer-links h4 {
            color: white;
            margin-bottom: 1rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none; /* Untuk kemudahan template standar */
            }
            .hero h2 {
                font-size: 2rem;
            }
            .stats-grid {
                flex-direction: column;
            }
            .footer-content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <div class="logo-container">
            <div class="logo-text">
                <h1>PERPUSTAKAAN UIB</h1>
                <p>Universitas Internasional Batam</p>
            </div>
        </div>
        <ul class="nav-links">
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Koleksi Buku</a></li>
            <li><a href="#">Tentang Kami</a></li>
            <li><a href="<?php echo e(route('login')); ?>" class="btn-login">Masuk</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Jelajahi Dunia Pengetahuan Tanpa Batas</h2>
        <p>Layanan Perpustakaan Digital Universitas Internasional Batam. Cari, pinjam, dan baca buku favorit Anda dalam satu platform terintegrasi.</p>
        
        <div class="search-bar">
            <input type="text" placeholder="Cari judul buku, penulis, atau ISBN di sini...">
            <button type="button">Cari Buku</button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="section-title">
            <h3>Layanan & Fasilitas</h3>
            <p>Berbagai kemudahan yang dapat Anda nikmati di Perpustakaan UIB</p>
        </div>

        <div class="feature-grid">
            <!-- Feature 1 -->
            <div class="feature-card">
                <div class="feature-icon">📚</div>
                <h4>Katalog Digital</h4>
                <p>Akses ribuan koleksi buku, jurnal ilmiah, dan literatur dari berbagai kategori Program Studi di UIB.</p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card">
                <div class="feature-icon">⏱️</div>
                <h4>Peminjaman Mudah</h4>
                <p>Pantau sisa kuota pinjam, tanggal jatuh tempo, dan riwayat sirkulasi peminjaman secara real-time.</p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card">
                <div class="feature-icon">🔖</div>
                <h4>Reservasi Buku</h4>
                <p>Buku sedang dipinjam? Lakukan reservasi antrean melalui sistem agar Anda mendapatkan giliran berikutnya.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="info-stats">
        <div class="stats-grid">
            <div class="stat-item">
                <h5>12K+</h5>
                <p>Koleksi Buku</p>
            </div>
            <div class="stat-item">
                <h5>8+</h5>
                <p>Kategori Program Studi</p>
            </div>
            <div class="stat-item">
                <h5>5K+</h5>
                <p>Anggota Aktif</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-brand">
                <h4>Perpustakaan UIB</h4>
                <p>Jl. Gajah Mada, Baloi Sei Ladi, Kota Batam, Kepulauan Riau</p>
                <p>Email: library@uib.ac.id</p>
            </div>
            <div class="footer-links">
                <h4>Tautan Pintar</h4>
                <ul>
                    <li><a href="<?php echo e(route('login')); ?>">Sistem Peminjaman</a></li>
                    <li><a href="https://www.uib.ac.id" target="_blank">Situs Resmi UIB</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo e(date('Y')); ?> Universitas Internasional Batam. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
<?php /**PATH C:\Users\Lenovo\Downloads\project_uas_echo_mysql\project_uas_echo\resources\views/welcome.blade.php ENDPATH**/ ?>