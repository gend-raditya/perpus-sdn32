<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('grants', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_pemberi');
            $table->string('kontak_pemberi')->nullable();
            $table->string('judul_buku');
            $table->string('penulis_buku');
            $table->integer('jumlah_eksemplar')->default(1);
            $table->text('deskripsi_kondisi')->nullable(); // <-- KOLOM INI YANG HILANG
            $table->string('foto_buku')->nullable();
            $table->enum('status_hibah', ['pending', 'disetujui', 'ditolak'])->default('pending');
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
