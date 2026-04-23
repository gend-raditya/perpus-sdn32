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
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            // Relasi ke user (siapa yang kasih hibah)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('judul_buku');
            $table->string('penulis_buku');
            $table->text('deskripsi_kondisi')->nullable();
            $table->string('foto_buku')->nullable(); // Path foto buat bukti

            // Status approval dari admin
            $table->enum('status_hibah', ['pending', 'disetujui', 'ditolak'])->default('pending');

            // Relasi ke tabel books (setelah disetujui, link ke ID buku aslinya)
            $table->unsignedBigInteger('book_id')->nullable();
            $table->foreign('book_id')->references('id')->on('books')->onDelete('set null');

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
