<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grants', function (Blueprint $table) {
            $table->string('nama_pemberi', 255)->nullable(false)->change();
            $table->string('kontak_pemberi', 13)->nullable()->change();
            $table->string('alamat_pengirim', 255)->change();
            $table->string('judul_buku', 255)->nullable()->change();
            $table->string('isbn', 50)->nullable()->change();
            $table->string('kategori_buku', 150)->nullable()->change();
            $table->string('kondisi_buku', 50)->nullable()->change();
            $table->string('bahasa', 50)->nullable()->change();
            $table->string('foto_buku', 255)->nullable()->change();
            $table->string('status_hibah', 20)->change();
            $table->string('penulis_buku', 255)->nullable()->change();
            $table->string('deskripsi_kondisi', 500)->nullable()->change();
            $table->string('sinopsis', 1000)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('grants', function (Blueprint $table) {
            $table->text('alamat_pengirim')->change();
            $table->text('kategori_buku')->nullable()->change();
            $table->text('deskripsi_kondisi')->nullable()->change();
            $table->text('sinopsis')->nullable()->change();
            $table->string('isbn')->nullable()->change();
            $table->string('foto_buku')->nullable()->change();
            $table->string('status_hibah')->change();
        });
    }
};
