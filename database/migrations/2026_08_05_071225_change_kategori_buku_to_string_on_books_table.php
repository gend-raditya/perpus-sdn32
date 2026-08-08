<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Mengubah tipe data kolom kategori_buku menjadi string biasa (VARCHAR)
            $table->string('kategori_buku', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->json('kategori_buku')->nullable()->change();
        });
    }
};
