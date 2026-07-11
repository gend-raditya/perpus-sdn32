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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit')->nullable();

            // Taruh di sini supaya otomatis berada setelah penerbit
            $table->integer('tahun_terbit');
            $table->integer('stok')->default(0);

            $table->string('isbn')->nullable();
            $table->string('kode_qr')->unique();
            $table->enum('asal_buku', ['pengadaan', 'hibah']);
            $table->enum('status', ['tersedia', 'dipinjam', 'rusak'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
