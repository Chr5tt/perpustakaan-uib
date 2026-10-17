-- ============================================================
-- DATABASE : perpustakaan_uib  (MySQL / MariaDB)
-- Project  : Sistem Perpustakaan - Universitas Internasional Batam
--
-- CARA PAKAI (pilih salah satu):
--   A) Disarankan - lewat Laravel:
--        php artisan migrate:fresh --seed
--   B) Import file ini langsung (phpMyAdmin / mysql CLI):
--        mysql -u root -p < database/sql/perpustakaan_uib.sql
--
-- Struktur tabel di bawah SAMA dengan migrasi Laravel, dan tabel
-- `migrations` sudah terisi, sehingga `php artisan migrate` setelah
-- import tidak akan membuat tabel dobel.
--
-- Akun demo (password di-hash bcrypt, bisa langsung login):
--   admin@uib.ac.id    / admin123   (admin)
--   farah@uib.ac.id    / farah123   (mahasiswa)
--   budi@uib.ac.id     / budi123    (dosen)
--   veynazy@gmail.com  / vey123     (mahasiswa)
-- ============================================================

CREATE DATABASE IF NOT EXISTS perpustakaan_uib CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE perpustakaan_uib;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reservations, loans, books, categories, users, sessions, cache, cache_locks, migrations;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    nim_nidn VARCHAR(255) NULL COMMENT 'NIM untuk mahasiswa, NIDN untuk dosen',
    phone VARCHAR(255) NULL,
    program_studi VARCHAR(255) NULL,
    role ENUM('mahasiswa','dosen','staff','admin') NOT NULL DEFAULT 'mahasiswa',
    photo VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY users_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY categories_slug_unique (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE books (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    year YEAR NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    stock INT NOT NULL DEFAULT 1,
    available INT NOT NULL DEFAULT 1,
    cover VARCHAR(255) NULL,
    description TEXT NULL,
    location VARCHAR(255) NULL COMMENT 'Lokasi rak buku',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY books_isbn_unique (isbn),
    CONSTRAINT books_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE loans (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    loan_code VARCHAR(255) NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    processed_by BIGINT UNSIGNED NULL,
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM('pending','active','returned','overdue') NOT NULL DEFAULT 'pending',
    fine INT NOT NULL DEFAULT 0 COMMENT 'Denda dalam rupiah',
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY loans_loan_code_unique (loan_code),
    CONSTRAINT loans_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT loans_book_id_foreign FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    CONSTRAINT loans_processed_by_foreign FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservations (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    reservation_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    status ENUM('waiting','ready','cancelled','completed') NOT NULL DEFAULT 'waiting',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT reservations_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT reservations_book_id_foreign FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    KEY sessions_user_id_index (user_id),
    KEY sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cache (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration BIGINT NOT NULL,
    KEY cache_expiration_index (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cache_locks (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration BIGINT NOT NULL,
    KEY cache_locks_expiration_index (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE migrations (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO migrations (migration, batch) VALUES
('2024_01_01_000001_create_users_table', 1),
('2024_01_01_000002_create_categories_table', 1),
('2024_01_01_000003_create_books_table', 1),
('2024_01_01_000004_create_loans_table', 1),
('2024_01_01_000005_create_reservations_table', 1),
('2026_05_26_171350_create_sessions_table', 1),
('2026_05_26_171401_create_cache_table', 1);

-- ============================================================
-- DATA AWAL
-- ============================================================

INSERT INTO users (id, name, email, nim_nidn, phone, program_studi, role, password, created_at, updated_at) VALUES
(1, 'Administrator', 'admin@uib.ac.id', 'ADM001', '08123456789', 'Perpustakaan', 'admin', '$2y$12$CAl4/l/WZf.DrNau40L2je12jHo/oJ0UveuUUHGcy8/iLdhUAckbi', NOW(), NOW()),
(2, 'Farah Nabilla Azri', 'farah@uib.ac.id', '2532046', '081234567890', 'Teknologi Informasi', 'mahasiswa', '$2y$12$d/XRggX/qxMI4JraFvl.8e6Udh8tscAX16Co6BGY30ztPlJ3Yk1mK', NOW(), NOW()),
(3, 'Dr. Budi Santoso', 'budi@uib.ac.id', '0012345678', '0821234567', 'Teknik Informatika', 'dosen', '$2y$12$YquBL10vD9hPjYha1jqbvOc6MXPpCgpxR2gaZvYuaSX2fQqnnMXb.', NOW(), NOW()),
(4, 'Veynazy', 'veynazy@gmail.com', '2532047', '081234567891', 'Teknologi Informasi', 'mahasiswa', '$2y$12$cA4k5IX/FGVZTh5nVtY6veXlCCzfYGo.vHKdUGJU7eGwNdFf8.tYS', NOW(), NOW());

INSERT INTO categories (id, name, slug, description, created_at, updated_at) VALUES
(1, 'Teknologi Informasi', 'teknologi-informasi', 'Buku tentang IT, pemrograman, dan komputer', NOW(), NOW()),
(2, 'Matematika', 'matematika', 'Buku matematika dan statistika', NOW(), NOW()),
(3, 'Manajemen', 'manajemen', 'Buku manajemen bisnis dan organisasi', NOW(), NOW()),
(4, 'Hukum', 'hukum', 'Buku hukum dan perundang-undangan', NOW(), NOW()),
(5, 'Teknik', 'teknik', 'Buku teknik sipil, mesin, dan elektro', NOW(), NOW()),
(6, 'Bahasa', 'bahasa', 'Buku bahasa Indonesia dan asing', NOW(), NOW()),
(7, 'Ekonomi', 'ekonomi', 'Buku ekonomi dan akuntansi', NOW(), NOW()),
(8, 'Umum', 'umum', 'Buku umum dan referensi', NOW(), NOW());

-- `available` = stock - jumlah peminjaman yang masih berjalan
INSERT INTO books (id, isbn, title, author, publisher, year, category_id, stock, available, location, description, created_at, updated_at) VALUES
(1, '978-0134685991', 'Effective Java', 'Joshua Bloch', 'Addison-Wesley', 2018, 1, 3, 3, 'Rak A1', 'Panduan terbaik untuk pemrograman Java modern.', NOW(), NOW()),
(2, '978-0596517748', 'JavaScript: The Good Parts', 'Douglas Crockford', 'O\'Reilly Media', 2008, 1, 2, 1, 'Rak A2', 'Memilah bagian terbaik JavaScript.', NOW(), NOW()),
(3, '978-0201633610', 'Design Patterns', 'Gang of Four', 'Addison-Wesley', 1994, 1, 4, 3, 'Rak A3', 'Pola desain perangkat lunak yang abadi.', NOW(), NOW()),
(4, '978-1491950357', 'Python Data Science Handbook', 'Jake VanderPlas', 'O\'Reilly Media', 2016, 1, 2, 2, 'Rak A4', 'Panduan lengkap data science dengan Python.', NOW(), NOW()),
(5, '978-0131101630', 'The C Programming Language', 'Kernighan & Ritchie', 'Prentice Hall', 1988, 1, 5, 4, 'Rak A5', 'Buku klasik bahasa pemrograman C.', NOW(), NOW()),
(6, '978-0071836326', 'Matematika Teknik', 'Erwin Kreyszig', 'McGraw-Hill', 2011, 2, 3, 3, 'Rak B1', 'Matematika teknik teknik lanjutan.', NOW(), NOW()),
(7, '978-0470458365', 'Statistika Dasar', 'Jay L. Devore', 'Wiley', 2009, 2, 2, 2, 'Rak B2', 'Statistika untuk ilmu rekayasa dan sains.', NOW(), NOW()),
(8, '978-0132350884', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 1, 3, 2, 'Rak A6', 'Panduan menulis kode bersih dan terstruktur.', NOW(), NOW()),
(9, '978-0201485677', 'The Mythical Man-Month', 'Frederick P. Brooks Jr.', 'Addison-Wesley', 1995, 1, 2, 2, 'Rak A7', 'Esai tentang rekayasa perangkat lunak.', NOW(), NOW()),
(10, '978-8120340817', 'Manajemen Strategis', 'David Fred R.', 'Salemba Empat', 2015, 3, 4, 4, 'Rak C1', 'Konsep manajemen strategis perusahaan.', NOW(), NOW()),
(11, '978-9791143461', 'Pengantar Hukum Indonesia', 'Sudikno Mertokusumo', 'Liberty', 2010, 4, 3, 3, 'Rak D1', 'Pengantar komprehensif hukum Indonesia.', NOW(), NOW()),
(12, '978-0136042594', 'Artificial Intelligence: A Modern Approach', 'Russell & Norvig', 'Pearson', 2020, 1, 2, 2, 'Rak A8', 'Buku teks AI paling komprehensif.', NOW(), NOW());

-- Tanggal peminjaman relatif terhadap hari import, supaya demo selalu relevan
INSERT INTO loans (id, loan_code, user_id, book_id, processed_by, loan_date, due_date, return_date, status, fine, created_at, updated_at) VALUES
(1, 'LN-2026-001', 2, 1, 1, CURDATE() - INTERVAL 40 DAY, CURDATE() - INTERVAL 26 DAY, CURDATE() - INTERVAL 27 DAY, 'returned', 0, NOW(), NOW()),
(2, 'LN-2026-002', 2, 3, 1, CURDATE() - INTERVAL 3 DAY,  CURDATE() + INTERVAL 11 DAY, NULL, 'active', 0, NOW(), NOW()),
(3, 'LN-2026-003', 4, 2, 1, CURDATE() - INTERVAL 25 DAY, CURDATE() - INTERVAL 11 DAY, NULL, 'overdue', 11000, NOW(), NOW()),
(4, 'LN-2026-004', 3, 5, 1, CURDATE() - INTERVAL 5 DAY,  CURDATE() + INTERVAL 9 DAY,  NULL, 'active', 0, NOW(), NOW()),
(5, 'LN-2026-005', 2, 8, 1, CURDATE() - INTERVAL 1 DAY,  CURDATE() + INTERVAL 13 DAY, NULL, 'active', 0, NOW(), NOW());

INSERT INTO reservations (id, user_id, book_id, reservation_date, expiry_date, status, created_at, updated_at) VALUES
(1, 2, 12, CURDATE() - INTERVAL 2 DAY, CURDATE() + INTERVAL 5 DAY, 'waiting', NOW(), NOW()),
(2, 4, 7,  CURDATE() - INTERVAL 4 DAY, CURDATE() + INTERVAL 2 DAY, 'ready', NOW(), NOW());
