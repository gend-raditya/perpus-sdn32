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
        Schema::table('books', function (Blueprint $table) {
            $table->string('bahasa')->nullable()->after('isbn');
            $table->integer('halaman')->nullable()->after('bahasa');
            $table->text('sinopsis')->nullable()->after('halaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['bahasa', 'halaman', 'sinopsis']);
        });
    }
};