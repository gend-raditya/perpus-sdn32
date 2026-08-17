<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->timestamps();
        });

        // Seed default sources to preserve backward compatibility with existing rows
        if (class_exists('\Illuminate\Support\Facades\DB')) {
            \Illuminate\Support\Facades\DB::table('book_sources')->insert([
                ['name' => 'pengadaan', 'code' => null, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'pembelian_dana_bos', 'code' => null, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'hibah', 'code' => null, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('book_sources');
    }
};
