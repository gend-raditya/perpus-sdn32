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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'denda_hilang')) {
                $table->decimal('denda_hilang', 12, 2)->nullable()->after('denda');
            }
        });

        Schema::table('transactions_archive', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions_archive', 'denda_hilang')) {
                $table->decimal('denda_hilang', 12, 2)->nullable()->after('denda');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'denda_hilang')) {
                $table->dropColumn('denda_hilang');
            }
        });

        Schema::table('transactions_archive', function (Blueprint $table) {
            if (Schema::hasColumn('transactions_archive', 'denda_hilang')) {
                $table->dropColumn('denda_hilang');
            }
        });
    }
};
