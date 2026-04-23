<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GrantController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::post('/books', [BookController::class, 'store'])->name('books.store');



// Halaman daftar pengajuan hibah
Route::get('/grants', [GrantController::class, 'index'])->name('grants.index');

// Proses Approval (Ubah hibah jadi buku + Generate QR)
Route::post('/grants/{id}/approve', [GrantController::class, 'approve'])->name('grants.approve');

// Route untuk menangkap hasil scan
Route::get('/scan/{kode_qr}', [App\Http\Controllers\BookController::class, 'showByScan'])->name('books.scan');


// Route untuk cetak label QR Code
Route::get('/books/print-labels', [BookController::class, 'printLabels'])->name('books.print');

// Resource route untuk MemberController (CRUD anggota)
Route::resource('members', MemberController::class);

// Route untuk cetak kartu anggota
Route::get('/members/{id}/print-card', [MemberController::class, 'printCard'])->name('members.print_card');



// Route untuk nampilin halaman scan (GET)
Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');

// Route untuk proses simpan data (POST)
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

// Route untuk daftar transaksi (Index)
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');


