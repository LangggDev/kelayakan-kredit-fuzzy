<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Parameter;
use App\Models\RuleFuzzy;

class FuzzyLogicSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan tabel terlebih dahulu
        Parameter::truncate();
        RuleFuzzy::truncate();

        // 1. PARAMETER C1: CHARACTER (Slik)
        $c1 = [
            ['S1', 'singleton', 1, 1, null, 'Slik 1 (Excelent, Very Good, Good)'],
            ['S2', 'singleton', 2, 2, null, 'Slik 2 (Medium, Bad 1)'],
            ['S3', 'singleton', 3, 3, null, 'Slik 3 (Bad 2, Worst)'],
        ];
        foreach ($c1 as $p) {
            Parameter::create([
                'kategori_5c'    => 'character',
                'nama_parameter' => 'skor_kredit_slik',
                'kode'           => 'C1',
                'himpunan'       => $p[0],
                'tipe_fungsi'    => $p[1],
                'a' => $p[2], 'b' => $p[3], 'c' => $p[4],
                'satuan'         => 'tipe',
                'keterangan'     => $p[5],
                'is_active'      => true,
            ]);
        }

        // 2. PARAMETER C2 - C5: Capacity, Capital, Collateral, Condition (0-100)
        // Himpunan: Tidak Layak (<=50), Layak (50-90), Sangat Layak (>=90)
        // Fuzzyfikasi:
        // Tidak Layak: linear_turun (a=50, b=70)
        // Layak: segitiga (a=50, b=70, c=90)
        // Sangat Layak: linear_naik (a=50, b=70)
        $kategori_lain = [
            ['capacity', 'capacity', 'C2'],
            ['capital', 'capital', 'C3'],
            ['collateral', 'collateral', 'C4'],
            ['condition', 'condition', 'C5'],
        ];

        foreach ($kategori_lain as $k) {
            $himpunan = [
                ['Tidak Layak',  'linear_turun', 50, 70, null, 'Skor <= 50 (Fuzzy turun 50-70)'],
                ['Layak',        'segitiga',     50, 70, 90,   'Skor 50 - 90 (Puncak di 70)'],
                ['Sangat Layak', 'linear_naik',  70, 90, null, 'Skor >= 90 (Fuzzy naik 70-90)'],
            ];
            foreach ($himpunan as $p) {
                Parameter::create([
                    'kategori_5c'    => $k[0],
                    'nama_parameter' => $k[1],
                    'kode'           => $k[2],
                    'himpunan'       => $p[0],
                    'tipe_fungsi'    => $p[1],
                    'a' => $p[2], 'b' => $p[3], 'c' => $p[4],
                    'satuan'         => 'skor',
                    'keterangan'     => $p[5],
                    'is_active'      => true,
                ]);
            }
        }

        // 3. RULE FUZZY (32 Rule)
        // Output Layak: linear_naik (a=70, b=100) -> z ranges 70-100
        // Output Tidak Layak: linear_turun (a=0, b=70) -> z ranges 0-70
        $rules = [
            [1, 'S1', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [2, 'S1', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 'Layak', 70, 100, 'linear_naik'],
            [3, 'S1', 'Sangat Layak', 'Sangat Layak', 'Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [4, 'S1', 'Sangat Layak', 'Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [5, 'S1', 'Layak', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [6, 'S1', 'Layak', 'Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [7, 'S1', 'Layak', 'Sangat Layak', 'Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [8, 'S1', 'Sangat Layak', 'Layak', 'Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [9, 'S1', 'Layak', 'Layak', 'Layak', 'Layak', 'Layak', 70, 100, 'linear_naik'],
            [10, 'S1', 'Sangat Layak', 'Layak', 'Layak', 'Layak', 'Layak', 70, 100, 'linear_naik'],
            [11, 'S1', 'Layak', 'Sangat Layak', 'Layak', 'Layak', 'Layak', 70, 100, 'linear_naik'],
            [12, 'S1', 'Layak', 'Layak', 'Sangat Layak', 'Layak', 'Layak', 70, 100, 'linear_naik'],
            [13, 'S2', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [14, 'S2', 'Layak', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [15, 'S2', 'Sangat Layak', 'Layak', 'Sangat Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [16, 'S2', 'Layak', 'Layak', 'Layak', 'Sangat Layak', 'Layak', 70, 100, 'linear_naik'],
            [17, 'S2', 'Layak', 'Layak', 'Layak', 'Layak', 'Layak', 70, 100, 'linear_naik'],
            [18, 'S1', 'Tidak Layak', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [19, 'S1', 'Layak', 'Tidak Layak', 'Sangat Layak', 'Sangat Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [20, 'S1', 'Layak', 'Layak', 'Tidak Layak', 'Sangat Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [21, 'S1', 'Layak', 'Layak', 'Sangat Layak', 'Tidak Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [22, 'S2', 'Tidak Layak', 'Layak', 'Layak', 'Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [23, 'S2', 'Layak', 'Tidak Layak', 'Layak', 'Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [24, 'S2', 'Layak', 'Layak', 'Tidak Layak', 'Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [25, 'S2', 'Layak', 'Layak', 'Layak', 'Tidak Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [26, 'S2', 'Tidak Layak', 'Tidak Layak', 'Layak', 'Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [27, 'S2', 'Layak', 'Tidak Layak', 'Tidak Layak', 'Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [28, 'S2', 'Layak', 'Layak', 'Tidak Layak', 'Tidak Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [29, 'S3', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Sangat Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [30, 'S3', 'Layak', 'Layak', 'Layak', 'Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [31, 'S3', 'Sangat Layak', 'Layak', 'Sangat Layak', 'Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
            [32, 'S3', 'Tidak Layak', 'Tidak Layak', 'Tidak Layak', 'Tidak Layak', 'Tidak Layak', 0, 70, 'linear_turun'],
        ];

        foreach ($rules as $r) {
            RuleFuzzy::create([
                'nomor_rule'  => $r[0],
                'character'   => $r[1],
                'capacity'    => $r[2],
                'capital'     => $r[3],
                'collateral'  => $r[4],
                'condition'   => $r[5],
                'kelayakan'   => $r[6],
                'output_a'    => $r[7],
                'output_b'    => $r[8],
                'output_tipe' => $r[9],
                'deskripsi'   => "Jika C1={$r[1]}, C2={$r[2]}, C3={$r[3]}, C4={$r[4]}, C5={$r[5]} maka {$r[6]}",
                'is_active'   => true,
            ]);
        }
    }
}
