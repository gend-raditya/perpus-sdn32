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
            if (!Schema::hasColumn('grants', 'penerbit_buku')) {
                $table->string('penerbit_buku')->nullable()->after('isbn');
            }

            if (!Schema::hasColumn('grants', 'tahun_terbit')) {
                $table->integer('tahun_terbit')->nullable()->after('penerbit_buku');
            }

            if (!Schema::hasColumn('grants', 'penulis_buku')) {
                $table->string('penulis_buku')->nullable()->after('tahun_terbit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grants', function (Blueprint $table) {
            if (Schema::hasColumn('grants', 'penerbit_buku')) {
                $table->dropColumn('penerbit_buku');
            }

            if (Schema::hasColumn('grants', 'tahun_terbit')) {
                $table->dropColumn('tahun_terbit');
            }

            if (Schema::hasColumn('grants', 'penulis_buku')) {
                $table->dropColumn('penulis_buku');
            }
        });
    }
};
