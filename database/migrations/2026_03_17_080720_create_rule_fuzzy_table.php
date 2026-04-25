<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rule_fuzzy', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_rule');
            // Anteseden 5C
            $table->string('character');
            $table->string('capacity');
            $table->string('capital');
            $table->string('collateral');
            $table->string('condition');
            // Konsekuen
            $table->string('kelayakan');
            $table->decimal('output_a', 10, 4)->default(0);
            $table->decimal('output_b', 10, 4)->default(100);
            $table->string('output_tipe')->default('linear_naik');
            $table->boolean('is_active')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rule_fuzzy');
    }
};
