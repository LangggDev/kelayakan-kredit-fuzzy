<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'analis', 'kepala_cabang', 'marketing'])
                  ->default('analis')
                  ->change();
        });

        Schema::create('kepala_cabang', function (Blueprint $table) {
            $table->id('id_kepala_cabang');
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('cabang')->nullable();
            $table->string('telepon')->nullable();
            $table->timestamps();
        });

        Schema::create('marketing_staff', function (Blueprint $table) {
            $table->id('id_marketing_staff');
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('area')->nullable();
            $table->string('telepon')->nullable();
            $table->timestamps();
        });

        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->enum('status_approval', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('catatan');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'id_user')->onDelete('set null')->after('status_approval');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('catatan_approval')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_analisis', function (Blueprint $table) {
            $table->dropColumn(['status_approval', 'approved_by', 'approved_at', 'catatan_approval']);
        });
        Schema::dropIfExists('marketing_staff');
        Schema::dropIfExists('kepala_cabang');
    }
};
