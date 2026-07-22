<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kredit_analis', function (Blueprint $table) {
            $table->id('id_kredit_analis');
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('jabatan')->nullable();
            $table->string('telepon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kredit_analis');
    }
};
