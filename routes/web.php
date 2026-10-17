<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ProfileController;

// 1. GUEST ROUTES
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 2. AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books (list bagi semua user login)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Reservations (semua user bisa buat/lihat/batalkan reservasi milik sendiri)
    Route::resource('reservations', ReservationController::class)->only(['index', 'store', 'destroy']);

    // Member mengajukan peminjaman (status pending, disetujui admin/staff)
    Route::post('/loans/request', [LoanController::class, 'requestLoan'])->name('loans.request');

    // Admin / Staff only routes
    Route::middleware('admin')->group(function () {
        // Books management (create, edit, delete)
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        // Members Management
        Route::resource('members', MemberController::class);

        // Categories Management
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

        // Loans Management
        Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
        Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
        Route::get('/loans/{loan}/edit', [LoanController::class, 'edit'])->name('loans.edit');
        Route::put('/loans/{loan}', [LoanController::class, 'update'])->name('loans.update');
        Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
        Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
        Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    });

    // Route dengan parameter diletakkan PALING AKHIR supaya /books/create dan
    // /loans/create tidak tertangkap sebagai {book} / {loan}.
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
});
