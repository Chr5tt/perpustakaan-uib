<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $users = [
            [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@uib.ac.id',
                'nim_nidn' => 'ADM001',
                'phone' => '08123456789',
                'program_studi' => 'Perpustakaan',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ],
            [
                'id' => 2,
                'name' => 'Farah Nabilla Azri',
                'email' => 'farah@uib.ac.id',
                'nim_nidn' => '2532046',
                'phone' => '081234567890',
                'program_studi' => 'Teknologi Informasi',
                'role' => 'mahasiswa',
                'password' => Hash::make('farah123'),
            ],
            [
                'id' => 3,
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi@uib.ac.id',
                'nim_nidn' => '0012345678',
                'phone' => '0821234567',
                'program_studi' => 'Teknik Informatika',
                'role' => 'dosen',
                'password' => Hash::make('budi123'),
            ],
            [
                'id' => 4,
                'name' => 'Veynazy',
                'email' => 'veynazy@gmail.com',
                'nim_nidn' => '2532047',
                'phone' => '081234567891',
                'program_studi' => 'Teknologi Informasi',
                'role' => 'mahasiswa',
                'password' => Hash::make('vey123'),
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['id' => $u['id']], $u);
        }

        // 2. Seed Categories
        $categories = [
            ['id' => 1, 'name' => 'Teknologi Informasi', 'slug' => 'teknologi-informasi', 'description' => 'Buku tentang IT, pemrograman, dan komputer'],
            ['id' => 2, 'name' => 'Matematika', 'slug' => 'matematika', 'description' => 'Buku matematika dan statistika'],
            ['id' => 3, 'name' => 'Manajemen', 'slug' => 'manajemen', 'description' => 'Buku manajemen bisnis dan organisasi'],
            ['id' => 4, 'name' => 'Hukum', 'slug' => 'hukum', 'description' => 'Buku hukum dan perundang-undangan'],
            ['id' => 5, 'name' => 'Teknik', 'slug' => 'teknik', 'description' => 'Buku teknik sipil, mesin, dan elektro'],
            ['id' => 6, 'name' => 'Bahasa', 'slug' => 'bahasa', 'description' => 'Buku bahasa Indonesia dan asing'],
            ['id' => 7, 'name' => 'Ekonomi', 'slug' => 'ekonomi', 'description' => 'Buku ekonomi dan akuntansi'],
            ['id' => 8, 'name' => 'Umum', 'slug' => 'umum', 'description' => 'Buku umum dan referensi'],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(['id' => $c['id']], $c);
        }

        // 3. Seed Books
        $books = [
            [
                'id' => 1,
                'isbn' => '978-0134685991',
                'title' => 'Effective Java',
                'author' => 'Joshua Bloch',
                'publisher' => 'Addison-Wesley',
                'year' => 2018,
                'category_id' => 1,
                'stock' => 3,
                'available' => 2,
                'location' => 'Rak A1',
                'description' => 'Panduan terbaik untuk pemrograman Java modern.',
            ],
            [
                'id' => 2,
                'isbn' => '978-0596517748',
                'title' => 'JavaScript: The Good Parts',
                'author' => 'Douglas Crockford',
                'publisher' => 'O\'Reilly Media',
                'year' => 2008,
                'category_id' => 1,
                'stock' => 2,
                'available' => 1,
                'location' => 'Rak A2',
                'description' => 'Memilah bagian terbaik JavaScript.',
            ],
            [
                'id' => 3,
                'isbn' => '978-0201633610',
                'title' => 'Design Patterns',
                'author' => 'Gang of Four',
                'publisher' => 'Addison-Wesley',
                'year' => 1994,
                'category_id' => 1,
                'stock' => 4,
                'available' => 4,
                'location' => 'Rak A3',
                'description' => 'Pola desain perangkat lunak yang abadi.',
            ],
            [
                'id' => 4,
                'isbn' => '978-1491950357',
                'title' => 'Python Data Science Handbook',
                'author' => 'Jake VanderPlas',
                'publisher' => 'O\'Reilly Media',
                'year' => 2016,
                'category_id' => 1,
                'stock' => 2,
                'available' => 2,
                'location' => 'Rak A4',
                'description' => 'Panduan lengkap data science dengan Python.',
            ],
            [
                'id' => 5,
                'isbn' => '978-0131101630',
                'title' => 'The C Programming Language',
                'author' => 'Kernighan & Ritchie',
                'publisher' => 'Prentice Hall',
                'year' => 1988,
                'category_id' => 1,
                'stock' => 5,
                'available' => 5,
                'location' => 'Rak A5',
                'description' => 'Buku klasik bahasa pemrograman C.',
            ],
            [
                'id' => 6,
                'isbn' => '978-0071836326',
                'title' => 'Matematika Teknik',
                'author' => 'Erwin Kreyszig',
                'publisher' => 'McGraw-Hill',
                'year' => 2011,
                'category_id' => 2,
                'stock' => 3,
                'available' => 3,
                'location' => 'Rak B1',
                'description' => 'Matematika teknik teknik lanjutan.',
            ],
            [
                'id' => 7,
                'isbn' => '978-0470458365',
                'title' => 'Statistika Dasar',
                'author' => 'Jay L. Devore',
                'publisher' => 'Wiley',
                'year' => 2009,
                'category_id' => 2,
                'stock' => 2,
                'available' => 1,
                'location' => 'Rak B2',
                'description' => 'Statistika untuk ilmu rekayasa dan sains.',
            ],
            [
                'id' => 8,
                'isbn' => '978-0132350884',
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'year' => 2008,
                'category_id' => 1,
                'stock' => 3,
                'available' => 3,
                'location' => 'Rak A6',
                'description' => 'Panduan menulis kode bersih dan terstruktur.',
            ],
            [
                'id' => 9,
                'isbn' => '978-0201485677',
                'title' => 'The Mythical Man-Month',
                'author' => 'Frederick P. Brooks Jr.',
                'publisher' => 'Addison-Wesley',
                'year' => 1995,
                'category_id' => 1,
                'stock' => 2,
                'available' => 2,
                'location' => 'Rak A7',
                'description' => 'Esai tentang rekayasa perangkat lunak.',
            ],
            [
                'id' => 10,
                'isbn' => '978-8120340817',
                'title' => 'Manajemen Strategis',
                'author' => 'David Fred R.',
                'publisher' => 'Salemba Empat',
                'year' => 2015,
                'category_id' => 3,
                'stock' => 4,
                'available' => 4,
                'location' => 'Rak C1',
                'description' => 'Konsep manajemen strategis perusahaan.',
            ],
            [
                'id' => 11,
                'isbn' => '978-9791143461',
                'title' => 'Pengantar Hukum Indonesia',
                'author' => 'Sudikno Mertokusumo',
                'publisher' => 'Liberty',
                'year' => 2010,
                'category_id' => 4,
                'stock' => 3,
                'available' => 3,
                'location' => 'Rak D1',
                'description' => 'Pengantar komprehensif hukum Indonesia.',
            ],
            [
                'id' => 12,
                'isbn' => '978-0136042594',
                'title' => 'Artificial Intelligence: A Modern Approach',
                'author' => 'Russell & Norvig',
                'publisher' => 'Pearson',
                'year' => 2020,
                'category_id' => 1,
                'stock' => 2,
                'available' => 1,
                'location' => 'Rak A8',
                'description' => 'Buku teks AI paling komprehensif.',
            ],
        ];

        foreach ($books as $b) {
            Book::updateOrCreate(['id' => $b['id']], $b);
        }

        // 4. Seed Loans (tanggal relatif terhadap hari ini supaya demo selalu relevan)
        $loans = [
            [
                'id' => 1, 'loan_code' => 'LN-2026-001', 'user_id' => 2, 'book_id' => 1, 'processed_by' => 1,
                'loan_date'   => now()->subDays(40)->toDateString(),
                'due_date'    => now()->subDays(26)->toDateString(),
                'return_date' => now()->subDays(27)->toDateString(),
                'status' => 'returned', 'fine' => 0,
            ],
            [
                'id' => 2, 'loan_code' => 'LN-2026-002', 'user_id' => 2, 'book_id' => 3, 'processed_by' => 1,
                'loan_date'   => now()->subDays(3)->toDateString(),
                'due_date'    => now()->addDays(11)->toDateString(),
                'return_date' => null, 'status' => 'active', 'fine' => 0,
            ],
            [
                'id' => 3, 'loan_code' => 'LN-2026-003', 'user_id' => 4, 'book_id' => 2, 'processed_by' => 1,
                'loan_date'   => now()->subDays(25)->toDateString(),
                'due_date'    => now()->subDays(11)->toDateString(),
                'return_date' => null, 'status' => 'overdue', 'fine' => 11000,
            ],
            [
                'id' => 4, 'loan_code' => 'LN-2026-004', 'user_id' => 3, 'book_id' => 5, 'processed_by' => 1,
                'loan_date'   => now()->subDays(5)->toDateString(),
                'due_date'    => now()->addDays(9)->toDateString(),
                'return_date' => null, 'status' => 'active', 'fine' => 0,
            ],
            [
                'id' => 5, 'loan_code' => 'LN-2026-005', 'user_id' => 2, 'book_id' => 8, 'processed_by' => 1,
                'loan_date'   => now()->subDays(1)->toDateString(),
                'due_date'    => now()->addDays(13)->toDateString(),
                'return_date' => null, 'status' => 'active', 'fine' => 0,
            ],
        ];

        foreach ($loans as $l) {
            Loan::updateOrCreate(['id' => $l['id']], $l);
        }

        // 5. Seed Reservations
        $reservations = [
            [
                'id' => 1, 'user_id' => 2, 'book_id' => 12,
                'reservation_date' => now()->subDays(2)->toDateString(),
                'expiry_date'      => now()->addDays(5)->toDateString(),
                'status' => 'waiting',
            ],
            [
                'id' => 2, 'user_id' => 4, 'book_id' => 7,
                'reservation_date' => now()->subDays(4)->toDateString(),
                'expiry_date'      => now()->addDays(2)->toDateString(),
                'status' => 'ready',
            ],
        ];

        foreach ($reservations as $r) {
            Reservation::updateOrCreate(['id' => $r['id']], $r);
        }

        // 6. Sinkronkan stok "available" dengan peminjaman yang benar-benar berjalan
        foreach (Book::all() as $book) {
            $borrowed = $book->loans()->whereIn('status', ['active', 'overdue'])->count();
            $book->update(['available' => max(0, $book->stock - $borrowed)]);
        }

        // 7. Hitung status terlambat & denda berjalan
        Loan::refreshOverdue();
    }
}
