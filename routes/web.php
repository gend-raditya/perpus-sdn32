<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GrantController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReportController;

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

    // Halaman Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // MANAJEMEN BUKU
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/detail-json', [BookController::class, 'getDetailJson'])->name('books.detail.json');
    Route::get('/books/print-labels', [BookController::class, 'printLabels'])->name('books.print');
    Route::get('/scan/{kode_qr}', [BookController::class, 'showByScan'])->name('books.scan');
    Route::resource('books', BookController::class)->except(['index', 'store']);

    // DENDA
    Route::resource('fines', FineController::class);

    // TRANSAKSI (AKSI KHUSUS)
    Route::patch('/transactions/{id}/kembali', [TransactionController::class, 'kembali'])->name('transaksi.kembali');
    Route::patch('/transactions/{id}/hilang', [TransactionController::class, 'hilang'])->name('transaksi.hilang');

    // TRANSAKSI (PEMINJAMAN)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::resource('transactions', TransactionController::class)->except(['index', 'create', 'store']);

    // RUTE LAPORAN
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    // HIBAH (GRANTS)
    Route::resource('grants', GrantController::class);
    Route::post('grants/{id}/approve', [GrantController::class, 'approve'])->name('grants.approve');
    Route::post('grants/{id}/reject', [GrantController::class, 'reject'])->name('grants.reject');

    // RAK (RACKS)
    Route::resource('racks', RackController::class);


    // ANGGOTA (MEMBERS)
    Route::post('/members/print-batch', [MemberController::class, 'printBatch'])->name('members.print_batch');
    Route::get('/members/{id}/print-card', [MemberController::class, 'printCard'])->name('members.print_card');

    Route::resource('members', MemberController::class)->only([
        'index',
        'create',
        'store',
        'show',
        'update',
        'destroy'
    ]);
});
