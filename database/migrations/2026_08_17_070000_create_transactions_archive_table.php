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
        Schema::create('transactions_archive', function (Blueprint $table) {
            $table->id();
            // keep nullable foreign keys to avoid FK problems when archiving
            $table->unsignedInteger('member_id')->nullable()->index();
            $table->unsignedInteger('book_id')->nullable()->index();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->date('deadline');
            $table->enum('status', ['pinjam', 'kembali', 'hilang'])->default('pinjam');
            $table->timestamps();
            $table->timestamp('archived_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_archive');
    }
};
