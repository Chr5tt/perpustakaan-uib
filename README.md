# Perpustakaan UIB – Setup (MySQL)

## Persyaratan
PHP >= 8.2 (ekstensi `pdo_mysql`), Composer, MySQL/MariaDB (XAMPP/Laragon juga bisa).

## Langkah
```bash
composer install
php artisan key:generate        # hanya jika APP_KEY di .env kosong

# 1. Buat database kosong
mysql -u root -e "CREATE DATABASE IF NOT EXISTS perpustakaan_uib CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Isi tabel + data demo (pilih A atau B, jangan keduanya)
php artisan migrate:fresh --seed                      # A) lewat Laravel (disarankan)
# mysql -u root < database/sql/perpustakaan_uib.sql   # B) import SQL langsung

php artisan storage:link        # agar cover buku yang diupload tampil
php artisan config:clear && php artisan view:clear
php artisan serve               # buka http://127.0.0.1:8000
```
Kalau MySQL Anda memakai password / port lain, ubah `DB_PASSWORD` / `DB_PORT` di `.env`.

## Akun demo
| Role | Email | Password |
|---|---|---|
| Admin | admin@uib.ac.id | admin123 |
| Mahasiswa | farah@uib.ac.id | farah123 |
| Dosen | budi@uib.ac.id | budi123 |
| Mahasiswa | veynazy@gmail.com | vey123 |

## Alur sistem
- **Member**: lihat buku → *Ajukan Peminjaman* (status pending) atau *Tambah Antrian* bila stok habis → pantau di Profil.
- **Admin/Staff**: Peminjaman → setujui pengajuan (stok berkurang), catat peminjaman langsung, kembalikan buku (denda Rp 1.000/hari otomatis, reservasi berikutnya jadi "Siap Diambil"), kelola buku/anggota/kategori.
