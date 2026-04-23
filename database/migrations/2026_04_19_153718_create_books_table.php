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
        $table->string('isbn')->unique()->nullable();
        $table->string('kode_qr')->unique(); // Ini buat nyimpen data string QR-nya
        $table->enum('asal_buku', ['pengadaan', 'hibah']); // Pembeda asal buku
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
