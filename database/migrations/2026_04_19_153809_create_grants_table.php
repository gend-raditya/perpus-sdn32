<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grants', function (Blueprint $table) {
            $table->id();

            // user_id dibuat nullable agar form publik bisa diisi tanpa perlu login
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('nama_pemberi');
            $table->string('kontak_pemberi')->nullable();
            $table->text('alamat_pengirim');

            // Tambahkan 2 kolom ini agar GrantController & views/admin tidak error saat memanggil $grant->judul_buku
            $table->string('judul_buku')->nullable();
            $table->string('penulis_buku')->nullable();

            // Kolom ini menyimpan array kategori dalam bentuk JSON/String
            $table->text('kategori_buku')->nullable();

            $table->integer('jumlah_eksemplar')->default(1);

            // Kolom menampung pesan/sinopsis/daftar judul dari form
            $table->text('deskripsi_kondisi')->nullable();

            $table->string('foto_buku')->nullable();
            $table->enum('status_hibah', ['pending', 'disetujui', 'ditolak'])->default('pending');

            // book_id nullable karena saat status masih pending belum masuk katalog buku
            $table->foreignId('book_id')->nullable()->constrained('books')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grants');
    }
};
