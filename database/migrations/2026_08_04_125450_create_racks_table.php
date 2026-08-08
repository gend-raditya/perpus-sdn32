<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('racks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: RAK-01
            $table->string('name');           // Contoh: Rak Lemari A (Buku Pelajaran)
            $table->string('location')->nullable(); // Contoh: Baris 1 - Sayap Kiri
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('racks');
    }
};
