<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_analisis', function (Blueprint $table) {
            $table->id('id_hasil_analisis');
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('calon_nasabah_id')->constrained('calon_nasabah', 'id_calon_nasabah')->onDelete('cascade');
            // Input 5C (nilai crisp)
            $table->decimal('skor_kredit', 10, 2);
            $table->decimal('penghasilan', 15, 2);
            $table->decimal('rasio_cicilan', 8, 4);
            $table->decimal('aset_bersih', 15, 2); 
            $table->decimal('nilai_agunan', 15, 2);
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->integer('jangka_waktu');
            $table->decimal('kondisi_ekonomi', 8, 2);
            // Hasil fuzzy
            $table->json('nilai_fuzzifikasi')->nullable();
            $table->json('detail_rule')->nullable();
            $table->decimal('nilai_defuzzifikasi', 10, 4)->nullable();
            $table->string('keputusan');
            $table->decimal('persentase_kelayakan', 5, 2)->nullable();
            // Skor per C
            $table->decimal('skor_character', 5, 2)->nullable();
            $table->decimal('skor_capacity', 5, 2)->nullable();
            $table->decimal('skor_capital', 5, 2)->nullable();
            $table->decimal('skor_collateral', 5, 2)->nullable();
            $table->decimal('skor_condition', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_analisis');
    }
};
