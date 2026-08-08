<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ubah enum ke string biar aman & fleksibel kedepannya
            $table->string('status')->default('pinjam')->change();
        });

        Schema::table('books', function (Blueprint $table) {
            $table->string('status')->default('tersedia')->change();
        });
    }

    public function down(): void
    {
        //
    }
};
