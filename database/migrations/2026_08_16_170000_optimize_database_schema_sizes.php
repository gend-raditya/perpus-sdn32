<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('judul', 255)->change();
            $table->string('penulis', 150)->change();
            $table->string('penerbit', 150)->nullable()->change();
            $table->string('isbn', 50)->nullable()->change();
            $table->string('kode_qr', 100)->change();
            $table->string('kategori_buku', 100)->nullable()->change();
            $table->string('bahasa', 50)->nullable()->change();
        });

        Schema::table('grants', function (Blueprint $table) {
            $table->string('nama_pemberi', 150)->change();
            $table->string('kontak_pemberi', 20)->nullable()->change();
            $table->string('alamat_pengirim', 500)->change();
            $table->string('judul_buku', 255)->nullable()->change();
            $table->string('penulis_buku', 150)->nullable()->change();
            $table->string('penerbit_buku', 150)->nullable()->change();
            $table->string('isbn', 50)->nullable()->change();
            $table->string('kategori_buku', 150)->nullable()->change();
            $table->string('kondisi_buku', 50)->nullable()->change();
            $table->string('bahasa', 50)->nullable()->change();
            $table->string('foto_buku', 255)->nullable()->change();
            $table->string('status_hibah', 20)->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('nisn', 30)->nullable()->change();
            $table->string('nama_lengkap', 150)->change();
            $table->string('peran', 20)->change();
            $table->string('no_hp', 20)->nullable()->change();
        });

        Schema::table('book_sources', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('code', 50)->nullable()->change();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('key', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('judul')->change();
            $table->string('penulis')->change();
            $table->string('penerbit')->nullable()->change();
            $table->string('isbn')->nullable()->change();
            $table->string('kode_qr')->change();
            $table->string('kategori_buku')->nullable()->change();
            $table->string('bahasa')->nullable()->change();
        });

        Schema::table('grants', function (Blueprint $table) {
            $table->string('nama_pemberi')->change();
            $table->string('kontak_pemberi')->nullable()->change();
            $table->string('alamat_pengirim')->change();
            $table->string('judul_buku')->nullable()->change();
            $table->string('penulis_buku')->nullable()->change();
            $table->string('penerbit_buku')->nullable()->change();
            $table->string('isbn')->nullable()->change();
            $table->string('kategori_buku')->nullable()->change();
            $table->string('kondisi_buku')->nullable()->change();
            $table->string('bahasa')->nullable()->change();
            $table->string('foto_buku')->nullable()->change();
            $table->string('status_hibah')->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('nisn')->nullable()->change();
            $table->string('nama_lengkap')->change();
            $table->string('peran')->change();
            $table->string('no_hp')->nullable()->change();
        });

        Schema::table('book_sources', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('code')->nullable()->change();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('key')->change();
        });
    }
};
