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
        if (Schema::hasTable('transactions_archive')) {
            Schema::table('transactions_archive', function (Blueprint $table) {
                if (!Schema::hasColumn('transactions_archive', 'denda')) {
                    $table->integer('denda')->nullable()->default(0)->after('deadline');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions_archive')) {
            Schema::table('transactions_archive', function (Blueprint $table) {
                if (Schema::hasColumn('transactions_archive', 'denda')) {
                    $table->dropColumn('denda');
                }
            });
        }
    }
};
