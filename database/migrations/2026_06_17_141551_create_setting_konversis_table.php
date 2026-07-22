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
        Schema::create('setting_konversis', function (Blueprint $table) {
            $table->id('id_setting_konversi');
            $table->string('kriteria')->unique();
            $table->float('batas_sangat_layak');
            $table->float('batas_tidak_layak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_konversis');
    }
};
