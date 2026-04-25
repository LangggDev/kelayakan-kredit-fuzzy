<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KreditAnalis;
use App\Models\KepalaCabang;
use App\Models\MarketingStaff;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['nik' => 'ADM001'],
            [
                'name'      => 'Administrator',
                'nik'       => 'ADM001',
                'email'     => 'admin@muf.co.id',
                'password'  => Hash::make('admin123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        $analis = User::firstOrCreate(
            ['nik' => 'KA001'],
            [
                'name'      => 'Budi Santoso',
                'nik'       => 'KA001',
                'email'     => 'budi@muf.co.id',
                'password'  => Hash::make('analis123'),
                'role'      => 'analis',
                'is_active' => true,
            ]
        );
        KreditAnalis::firstOrCreate(
            ['user_id' => $analis->id],
            [
                'nip'     => 'KA-2024-001',
                'jabatan' => 'Kredit Analis Senior',
                'telepon' => '081234567890',
            ]
        );

        $kc = User::firstOrCreate(
            ['nik' => 'KC001'],
            [
                'name'      => 'Andi Wijaya',
                'nik'       => 'KC001',
                'email'     => 'andi@muf.co.id',
                'password'  => Hash::make('kc12345678'),
                'role'      => 'kepala_cabang',
                'is_active' => true,
            ]
        );
        KepalaCabang::firstOrCreate(
            ['user_id' => $kc->id],
            [
                'nip'     => 'KC-2024-001',
                'cabang'  => 'Cabang Jakarta Selatan',
                'telepon' => '081234567891',
            ]
        );

        $mkt = User::firstOrCreate(
            ['nik' => 'MKT001'],
            [
                'name'      => 'Sari Dewi',
                'nik'       => 'MKT001',
                'email'     => 'sari@muf.co.id',
                'password'  => Hash::make('mkt12345678'),
                'role'      => 'marketing',
                'is_active' => true,
            ]
        );
        MarketingStaff::firstOrCreate(
            ['user_id' => $mkt->id],
            [
                'nip'     => 'MKT-2024-001',
                'area'    => 'Jabodetabek',
                'telepon' => '081234567892',
            ]
        );

        $this->command->info('');
        $this->command->info('✅ Akun demo berhasil dibuat. Login menggunakan NIK:');
        $this->command->table(
            ['Role', 'NIK (Login)', 'Password', 'Nama'],
            [
                ['Admin',         'ADM001',  'admin123',    'Administrator'],
                ['Kredit Analis', 'KA001',   'analis123',   'Budi Santoso'],
                ['Kepala Cabang', 'KC001',   'kc12345678',  'Andi Wijaya'],
                ['Marketing',     'MKT001',  'mkt12345678', 'Sari Dewi'],
            ]
        );
    }
}
