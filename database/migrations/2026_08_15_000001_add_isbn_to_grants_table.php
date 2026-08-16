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
            if (!Schema::hasColumn('grants', 'isbn')) {
                $table->string('isbn')->nullable()->after('judul_buku');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grants', function (Blueprint $table) {
            if (Schema::hasColumn('grants', 'isbn')) {
                $table->dropColumn('isbn');
            }
        });
    }
};
