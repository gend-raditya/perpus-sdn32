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
        Schema::table('grants', function (Blueprint $table) {
            // Menambahkan kolom sinopsis dengan tipe TEXT setelah kolom jumlah_eksemplar
            // Dibuat nullable agar data lama yang belum punya sinopsis tidak error
            $table->text('sinopsis')->nullable()->after('jumlah_eksemplar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grants', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('sinopsis');
        });
    }
};
