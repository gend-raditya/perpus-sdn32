<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GrantController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\LoginController;

// 1. HALAMAN PUBLIK (Bisa diakses siapa saja tanpa login)
Route::get('/', [LandingPageController::class, 'index'])->name('welcome');

// Halaman form hibah untuk publik
Route::get('/hibah-buku', [LandingPageController::class, 'createGrant'])->name('public.grants.create');
Route::post('/hibah-buku', [LandingPageController::class, 'storeGrant'])->name('public.grants.store');
Route::get('/hibah-buku/sukses/{id}', [LandingPageController::class, 'success'])->name('public.grants.success');

// RUTE AUTENTIKASI ADMIN
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// 2. SEMUA RUTE PENGELOLA/ADMIN (Wajib Login)
Route::middleware(['auth'])->group(function () {

    // Halaman Dashboard Utama Lo
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // MANAJEMEN BUKU
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/detail-json', [BookController::class, 'getDetailJson'])->name('books.detail.json');
    Route::get('/books/print-labels', [BookController::class, 'printLabels'])->name('books.print');
    Route::get('/scan/{kode_qr}', [BookController::class, 'showByScan'])->name('books.scan');

    // HIBAH (GRANTS)
    Route::resource('grants', GrantController::class);
    Route::post('grants/{id}/approve', [GrantController::class, 'approve'])->name('grants.approve');
    Route::post('grants/{id}/reject', [GrantController::class, 'reject'])->name('grants.reject');

    // ANGGOTA (MEMBERS)
    Route::resource('members', MemberController::class);
    Route::get('/members/{id}/print-card', [MemberController::class, 'printCard'])->name('members.print_card');

    // TRANSAKSI (PEMINJAMAN)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
});
