<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingKonversiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SettingKonversi::truncate();

        \App\Models\SettingKonversi::create([
            'kriteria' => 'capacity',
            'batas_sangat_layak' => 90,
            'batas_tidak_layak' => 50,
        ]);

        \App\Models\SettingKonversi::create([
            'kriteria' => 'capital',
            'batas_sangat_layak' => 90,
            'batas_tidak_layak' => 50,
        ]);

        \App\Models\SettingKonversi::create([
            'kriteria' => 'collateral',
            'batas_sangat_layak' => 90,
            'batas_tidak_layak' => 50,
        ]);
    }
}
