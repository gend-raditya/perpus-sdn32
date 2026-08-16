<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grants', function (Blueprint $table) {
            if (!Schema::hasColumn('grants', 'kondisi_buku')) {
                $table->string('kondisi_buku')->nullable()->after('kategori_buku');
            }
            if (!Schema::hasColumn('grants', 'jumlah_halaman')) {
                $table->integer('jumlah_halaman')->nullable()->after('kondisi_buku');
            }
            if (!Schema::hasColumn('grants', 'bahasa')) {
                $table->string('bahasa')->nullable()->after('jumlah_halaman');
            }
        });
    }

    public function down(): void
    {
        Schema::table('grants', function (Blueprint $table) {
            if (Schema::hasColumn('grants', 'kondisi_buku')) {
                $table->dropColumn('kondisi_buku');
            }
            if (Schema::hasColumn('grants', 'jumlah_halaman')) {
                $table->dropColumn('jumlah_halaman');
            }
            if (Schema::hasColumn('grants', 'bahasa')) {
                $table->dropColumn('bahasa');
            }
        });
    }
};
