<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_5c');
            $table->string('nama_parameter');
            $table->string('kode'); 
            $table->string('himpunan');
            $table->string('tipe_fungsi');
            $table->decimal('a', 20, 4);
            $table->decimal('b', 20, 4);
            $table->decimal('c', 20, 4)->nullable();
            $table->decimal('d', 20, 4)->nullable();
            $table->string('satuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter');
    }
};
