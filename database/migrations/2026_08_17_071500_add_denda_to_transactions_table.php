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
        // Safe-guard: only add column if it doesn't exist to avoid duplicate column errors
        if (!Schema::hasColumn('transactions', 'denda')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->integer('denda')->nullable()->default(0)->after('deadline');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('denda');
        });
    }
};
