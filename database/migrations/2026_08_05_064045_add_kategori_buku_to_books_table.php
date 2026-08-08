<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Menambahkan kolom kategori_buku (JSON/Text) setelah kolom penerbit
            $table->json('kategori_buku')->nullable()->after('penerbit');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('kategori_buku');
        });
    }
};
