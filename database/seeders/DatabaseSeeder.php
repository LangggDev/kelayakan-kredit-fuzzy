<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KreditAnalis;
use App\Models\KepalaCabang;
use App\Models\MarketingStaff;
use App\Models\Parameter;
use App\Models\RuleFuzzy;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'      => 'Administrator',
            'nik'       => 'ADM001',
            'email'     => 'admin@muf.co.id',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $analis = User::create([
            'name'      => 'Budi Santoso',
            'nik'       => 'KA001',
            'email'     => 'budi@muf.co.id',
            'password'  => Hash::make('analis123'),
            'role'      => 'analis',
            'is_active' => true,
        ]);
        KreditAnalis::create([
            'user_id' => $analis->id,
            'nip'     => 'KA-2024-001',
            'jabatan' => 'Kredit Analis Senior',
            'telepon' => '081234567890',
        ]);

        $kc = User::create([
            'name'      => 'Andi Wijaya',
            'nik'       => 'KC001',
            'email'     => 'andi@muf.co.id',
            'password'  => Hash::make('kc12345678'),
            'role'      => 'kepala_cabang',
            'is_active' => true,
        ]);
        KepalaCabang::create([
            'user_id' => $kc->id,
            'nip'     => 'KC-2024-001',
            'cabang'  => 'Cabang Jakarta Selatan',
            'telepon' => '081234567891',
        ]);

        $mkt = User::create([
            'name'      => 'Sari Dewi',
            'nik'       => 'MKT001',
            'email'     => 'sari@muf.co.id',
            'password'  => Hash::make('mkt12345678'),
            'role'      => 'marketing',
            'is_active' => true,
        ]);
        MarketingStaff::create([
            'user_id' => $mkt->id,
            'nip'     => 'MKT-2024-001',
            'area'    => 'Jabodetabek',
            'telepon' => '081234567892',
        ]);

        // C1 — CHARACTER: Skor Kredit BI Checking (0–100)
        $c1 = [
            ['buruk', 'linear_turun', 0,   40,  null, 'Kolektibilitas 3-5, sering menunggak / macet'],
            ['cukup', 'segitiga',     30,  55,  75,   'Kolektibilitas 2, ada keterlambatan minor'],
            ['baik',  'linear_naik',  65,  100, null, 'Kolektibilitas 1, selalu membayar tepat waktu'],
        ];
        foreach ($c1 as $p) {
            Parameter::create([
                'kategori_5c'    => 'character',
                'nama_parameter' => 'skor_kredit',
                'kode'           => 'C1',
                'himpunan'       => $p[0],
                'tipe_fungsi'    => $p[1],
                'a' => $p[2], 'b' => $p[3], 'c' => $p[4],
                'satuan'         => 'skor',
                'keterangan'     => $p[5],
                'is_active'      => true,
            ]);
        }

        // C2 — CAPACITY: Rasio Cicilan / Penghasilan — DSCR (%)
        $c2 = [
            ['tinggi', 'linear_turun', 0,   30,  null, 'Cicilan < 30% penghasilan, kemampuan bayar sangat baik'],
            ['sedang', 'segitiga',     25,  37,  52,   'Cicilan 25-52% penghasilan, kemampuan bayar cukup'],
            ['rendah', 'linear_naik',  40,  80,  null, 'Cicilan > 40% penghasilan, beban terlalu berat'],
        ];
        foreach ($c2 as $p) {
            Parameter::create([
                'kategori_5c'    => 'capacity',
                'nama_parameter' => 'rasio_cicilan',
                'kode'           => 'C2',
                'himpunan'       => $p[0],
                'tipe_fungsi'    => $p[1],
                'a' => $p[2], 'b' => $p[3], 'c' => $p[4],
                'satuan'         => '%',
                'keterangan'     => $p[5],
                'is_active'      => true,
            ]);
        }

        // C3 — CAPITAL: Aset Bersih (Aset - Hutang) dalam Rp
        $c3 = [
            ['kecil',  'linear_turun', 0,          50000000,  null,       'Aset bersih < 50 juta, modal sangat terbatas'],
            ['sedang', 'segitiga',     25000000,   100000000, 200000000,  'Aset bersih 25-200 juta, modal cukup'],
            ['besar',  'linear_naik',  150000000,  500000000, null,       'Aset bersih > 150 juta, modal kuat'],
        ];
        foreach ($c3 as $p) {
            Parameter::create([
                'kategori_5c'    => 'capital',
                'nama_parameter' => 'aset_bersih',
                'kode'           => 'C3',
                'himpunan'       => $p[0],
                'tipe_fungsi'    => $p[1],
                'a' => $p[2], 'b' => $p[3], 'c' => $p[4],
                'satuan'         => 'Rp',
                'keterangan'     => $p[5],
                'is_active'      => true,
            ]);
        }

        // C4 — COLLATERAL: LTV Ratio = (Pinjaman / Nilai Agunan) × 100 (%)
        $c4 = [
            ['rendah',  'linear_naik',  80,  150, null, 'LTV > 80%, agunan tidak mencukupi pinjaman'],
            ['sedang',  'segitiga',     60,  80,  115,  'LTV 60-115%, agunan cukup memadai'],
            ['tinggi',  'linear_turun', 0,   70,  null, 'LTV < 70%, agunan sangat kuat / over-collateral'],
        ];
        foreach ($c4 as $p) {
            Parameter::create([
                'kategori_5c'    => 'collateral',
                'nama_parameter' => 'ltv_ratio',
                'kode'           => 'C4',
                'himpunan'       => $p[0],
                'tipe_fungsi'    => $p[1],
                'a' => $p[2], 'b' => $p[3], 'c' => $p[4],
                'satuan'         => '%',
                'keterangan'     => $p[5],
                'is_active'      => true,
            ]);
        }

        // C5 — CONDITION: Skor Kondisi Ekonomi & Sektor Usaha (0–100)
        $c5 = [
            ['buruk', 'linear_turun', 0,  40,  null, 'Kondisi ekonomi lesu / resesi, risiko gagal bayar tinggi'],
            ['cukup', 'segitiga',     30, 55,  75,   'Kondisi ekonomi normal / stabil'],
            ['baik',  'linear_naik',  60, 100, null, 'Kondisi ekonomi tumbuh, sektor usaha prospektif'],
        ];
        foreach ($c5 as $p) {
            Parameter::create([
                'kategori_5c'    => 'condition',
                'nama_parameter' => 'kondisi_ekonomi',
                'kode'           => 'C5',
                'himpunan'       => $p[0],
                'tipe_fungsi'    => $p[1],
                'a' => $p[2], 'b' => $p[3], 'c' => $p[4],
                'satuan'         => 'skor',
                'keterangan'     => $p[5],
                'is_active'      => true,
            ]);
        }

        $rules = [
            [  1, 'baik', 'tinggi', 'besar', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan kuat, kondisi baik [skor:15]'],
            [  2, 'baik', 'tinggi', 'besar', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan kuat, kondisi cukup [skor:14]'],
            [  3, 'baik', 'tinggi', 'besar', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan kuat, kondisi buruk [skor:12]'],
            [  4, 'baik', 'tinggi', 'besar', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan cukup, kondisi baik [skor:14]'],
            [  5, 'baik', 'tinggi', 'besar', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan cukup, kondisi cukup [skor:13]'],
            [  6, 'baik', 'tinggi', 'besar', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan cukup, kondisi buruk [skor:11]'],
            [  7, 'baik', 'tinggi', 'besar', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan lemah, kondisi baik [skor:13]'],
            [  8, 'baik', 'tinggi', 'besar', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan lemah, kondisi cukup [skor:12]'],
            [  9, 'baik', 'tinggi', 'besar', 'rendah', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal besar, agunan lemah, kondisi buruk [skor:10]'],
            [ 10, 'baik', 'tinggi', 'sedang', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan kuat, kondisi baik [skor:14]'],
            [ 11, 'baik', 'tinggi', 'sedang', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan kuat, kondisi cukup [skor:13]'],
            [ 12, 'baik', 'tinggi', 'sedang', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan kuat, kondisi buruk [skor:11]'],
            [ 13, 'baik', 'tinggi', 'sedang', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan cukup, kondisi baik [skor:13]'],
            [ 14, 'baik', 'tinggi', 'sedang', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan cukup, kondisi cukup [skor:12]'],
            [ 15, 'baik', 'tinggi', 'sedang', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan cukup, kondisi buruk [skor:10]'],
            [ 16, 'baik', 'tinggi', 'sedang', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan lemah, kondisi baik [skor:12]'],
            [ 17, 'baik', 'tinggi', 'sedang', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan lemah, kondisi cukup [skor:11]'],
            [ 18, 'baik', 'tinggi', 'sedang', 'rendah', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal sedang, agunan lemah, kondisi buruk [skor:9]'],
            [ 19, 'baik', 'tinggi', 'kecil', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan kuat, kondisi baik [skor:13]'],
            [ 20, 'baik', 'tinggi', 'kecil', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan kuat, kondisi cukup [skor:12]'],
            [ 21, 'baik', 'tinggi', 'kecil', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan kuat, kondisi buruk [skor:10]'],
            [ 22, 'baik', 'tinggi', 'kecil', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan cukup, kondisi baik [skor:12]'],
            [ 23, 'baik', 'tinggi', 'kecil', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan cukup, kondisi cukup [skor:11]'],
            [ 24, 'baik', 'tinggi', 'kecil', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan cukup, kondisi buruk [skor:9]'],
            [ 25, 'baik', 'tinggi', 'kecil', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan lemah, kondisi baik [skor:11]'],
            [ 26, 'baik', 'tinggi', 'kecil', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas tinggi, modal kecil, agunan lemah, kondisi cukup [skor:10]'],
            [ 27, 'baik', 'tinggi', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas tinggi, modal kecil, agunan lemah, kondisi buruk [skor:8]'],
            [ 28, 'baik', 'sedang', 'besar', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan kuat, kondisi baik [skor:14]'],
            [ 29, 'baik', 'sedang', 'besar', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan kuat, kondisi cukup [skor:13]'],
            [ 30, 'baik', 'sedang', 'besar', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan kuat, kondisi buruk [skor:11]'],
            [ 31, 'baik', 'sedang', 'besar', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan cukup, kondisi baik [skor:13]'],
            [ 32, 'baik', 'sedang', 'besar', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan cukup, kondisi cukup [skor:12]'],
            [ 33, 'baik', 'sedang', 'besar', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan cukup, kondisi buruk [skor:10]'],
            [ 34, 'baik', 'sedang', 'besar', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan lemah, kondisi baik [skor:12]'],
            [ 35, 'baik', 'sedang', 'besar', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan lemah, kondisi cukup [skor:11]'],
            [ 36, 'baik', 'sedang', 'besar', 'rendah', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal besar, agunan lemah, kondisi buruk [skor:9]'],
            [ 37, 'baik', 'sedang', 'sedang', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan kuat, kondisi baik [skor:13]'],
            [ 38, 'baik', 'sedang', 'sedang', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan kuat, kondisi cukup [skor:12]'],
            [ 39, 'baik', 'sedang', 'sedang', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan kuat, kondisi buruk [skor:10]'],
            [ 40, 'baik', 'sedang', 'sedang', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan cukup, kondisi baik [skor:12]'],
            [ 41, 'baik', 'sedang', 'sedang', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan cukup, kondisi cukup [skor:11]'],
            [ 42, 'baik', 'sedang', 'sedang', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan cukup, kondisi buruk [skor:9]'],
            [ 43, 'baik', 'sedang', 'sedang', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan lemah, kondisi baik [skor:11]'],
            [ 44, 'baik', 'sedang', 'sedang', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal sedang, agunan lemah, kondisi cukup [skor:10]'],
            [ 45, 'baik', 'sedang', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas sedang, modal sedang, agunan lemah, kondisi buruk [skor:8]'],
            [ 46, 'baik', 'sedang', 'kecil', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal kecil, agunan kuat, kondisi baik [skor:12]'],
            [ 47, 'baik', 'sedang', 'kecil', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal kecil, agunan kuat, kondisi cukup [skor:11]'],
            [ 48, 'baik', 'sedang', 'kecil', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal kecil, agunan kuat, kondisi buruk [skor:9]'],
            [ 49, 'baik', 'sedang', 'kecil', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal kecil, agunan cukup, kondisi baik [skor:11]'],
            [ 50, 'baik', 'sedang', 'kecil', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal kecil, agunan cukup, kondisi cukup [skor:10]'],
            [ 51, 'baik', 'sedang', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas sedang, modal kecil, agunan cukup, kondisi buruk [skor:8]'],
            [ 52, 'baik', 'sedang', 'kecil', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal kecil, agunan lemah, kondisi baik [skor:10]'],
            [ 53, 'baik', 'sedang', 'kecil', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas sedang, modal kecil, agunan lemah, kondisi cukup [skor:9]'],
            [ 54, 'baik', 'sedang', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas sedang, modal kecil, agunan lemah, kondisi buruk [skor:7]'],
            [ 55, 'baik', 'rendah', 'besar', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal besar, agunan kuat, kondisi baik [skor:12]'],
            [ 56, 'baik', 'rendah', 'besar', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal besar, agunan kuat, kondisi cukup [skor:11]'],
            [ 57, 'baik', 'rendah', 'besar', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal besar, agunan kuat, kondisi buruk [skor:9]'],
            [ 58, 'baik', 'rendah', 'besar', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal besar, agunan cukup, kondisi baik [skor:11]'],
            [ 59, 'baik', 'rendah', 'besar', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal besar, agunan cukup, kondisi cukup [skor:10]'],
            [ 60, 'baik', 'rendah', 'besar', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal besar, agunan cukup, kondisi buruk [skor:8]'],
            [ 61, 'baik', 'rendah', 'besar', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal besar, agunan lemah, kondisi baik [skor:10]'],
            [ 62, 'baik', 'rendah', 'besar', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal besar, agunan lemah, kondisi cukup [skor:9]'],
            [ 63, 'baik', 'rendah', 'besar', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal besar, agunan lemah, kondisi buruk [skor:7]'],
            [ 64, 'baik', 'rendah', 'sedang', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal sedang, agunan kuat, kondisi baik [skor:11]'],
            [ 65, 'baik', 'rendah', 'sedang', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal sedang, agunan kuat, kondisi cukup [skor:10]'],
            [ 66, 'baik', 'rendah', 'sedang', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal sedang, agunan kuat, kondisi buruk [skor:8]'],
            [ 67, 'baik', 'rendah', 'sedang', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal sedang, agunan cukup, kondisi baik [skor:10]'],
            [ 68, 'baik', 'rendah', 'sedang', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal sedang, agunan cukup, kondisi cukup [skor:9]'],
            [ 69, 'baik', 'rendah', 'sedang', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal sedang, agunan cukup, kondisi buruk [skor:7]'],
            [ 70, 'baik', 'rendah', 'sedang', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal sedang, agunan lemah, kondisi baik [skor:9]'],
            [ 71, 'baik', 'rendah', 'sedang', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal sedang, agunan lemah, kondisi cukup [skor:8]'],
            [ 72, 'baik', 'rendah', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal sedang, agunan lemah, kondisi buruk [skor:6]'],
            [ 73, 'baik', 'rendah', 'kecil', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal kecil, agunan kuat, kondisi baik [skor:10]'],
            [ 74, 'baik', 'rendah', 'kecil', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal kecil, agunan kuat, kondisi cukup [skor:9]'],
            [ 75, 'baik', 'rendah', 'kecil', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal kecil, agunan kuat, kondisi buruk [skor:7]'],
            [ 76, 'baik', 'rendah', 'kecil', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter baik, kapasitas rendah, modal kecil, agunan cukup, kondisi baik [skor:9]'],
            [ 77, 'baik', 'rendah', 'kecil', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal kecil, agunan cukup, kondisi cukup [skor:8]'],
            [ 78, 'baik', 'rendah', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal kecil, agunan cukup, kondisi buruk [skor:6]'],
            [ 79, 'baik', 'rendah', 'kecil', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal kecil, agunan lemah, kondisi baik [skor:8]'],
            [ 80, 'baik', 'rendah', 'kecil', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal kecil, agunan lemah, kondisi cukup [skor:7]'],
            [ 81, 'baik', 'rendah', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter baik, kapasitas rendah, modal kecil, agunan lemah, kondisi buruk [skor:5]'],
            [ 82, 'cukup', 'tinggi', 'besar', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan kuat, kondisi baik [skor:14]'],
            [ 83, 'cukup', 'tinggi', 'besar', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan kuat, kondisi cukup [skor:13]'],
            [ 84, 'cukup', 'tinggi', 'besar', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan kuat, kondisi buruk [skor:11]'],
            [ 85, 'cukup', 'tinggi', 'besar', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan cukup, kondisi baik [skor:13]'],
            [ 86, 'cukup', 'tinggi', 'besar', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan cukup, kondisi cukup [skor:12]'],
            [ 87, 'cukup', 'tinggi', 'besar', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan cukup, kondisi buruk [skor:10]'],
            [ 88, 'cukup', 'tinggi', 'besar', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan lemah, kondisi baik [skor:12]'],
            [ 89, 'cukup', 'tinggi', 'besar', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan lemah, kondisi cukup [skor:11]'],
            [ 90, 'cukup', 'tinggi', 'besar', 'rendah', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal besar, agunan lemah, kondisi buruk [skor:9]'],
            [ 91, 'cukup', 'tinggi', 'sedang', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan kuat, kondisi baik [skor:13]'],
            [ 92, 'cukup', 'tinggi', 'sedang', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan kuat, kondisi cukup [skor:12]'],
            [ 93, 'cukup', 'tinggi', 'sedang', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan kuat, kondisi buruk [skor:10]'],
            [ 94, 'cukup', 'tinggi', 'sedang', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan cukup, kondisi baik [skor:12]'],
            [ 95, 'cukup', 'tinggi', 'sedang', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan cukup, kondisi cukup [skor:11]'],
            [ 96, 'cukup', 'tinggi', 'sedang', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan cukup, kondisi buruk [skor:9]'],
            [ 97, 'cukup', 'tinggi', 'sedang', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan lemah, kondisi baik [skor:11]'],
            [ 98, 'cukup', 'tinggi', 'sedang', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan lemah, kondisi cukup [skor:10]'],
            [ 99, 'cukup', 'tinggi', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas tinggi, modal sedang, agunan lemah, kondisi buruk [skor:8]'],
            [100, 'cukup', 'tinggi', 'kecil', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan kuat, kondisi baik [skor:12]'],
            [101, 'cukup', 'tinggi', 'kecil', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan kuat, kondisi cukup [skor:11]'],
            [102, 'cukup', 'tinggi', 'kecil', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan kuat, kondisi buruk [skor:9]'],
            [103, 'cukup', 'tinggi', 'kecil', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan cukup, kondisi baik [skor:11]'],
            [104, 'cukup', 'tinggi', 'kecil', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan cukup, kondisi cukup [skor:10]'],
            [105, 'cukup', 'tinggi', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan cukup, kondisi buruk [skor:8]'],
            [106, 'cukup', 'tinggi', 'kecil', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan lemah, kondisi baik [skor:10]'],
            [107, 'cukup', 'tinggi', 'kecil', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan lemah, kondisi cukup [skor:9]'],
            [108, 'cukup', 'tinggi', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas tinggi, modal kecil, agunan lemah, kondisi buruk [skor:7]'],
            [109, 'cukup', 'sedang', 'besar', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan kuat, kondisi baik [skor:13]'],
            [110, 'cukup', 'sedang', 'besar', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan kuat, kondisi cukup [skor:12]'],
            [111, 'cukup', 'sedang', 'besar', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan kuat, kondisi buruk [skor:10]'],
            [112, 'cukup', 'sedang', 'besar', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan cukup, kondisi baik [skor:12]'],
            [113, 'cukup', 'sedang', 'besar', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan cukup, kondisi cukup [skor:11]'],
            [114, 'cukup', 'sedang', 'besar', 'sedang', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan cukup, kondisi buruk [skor:9]'],
            [115, 'cukup', 'sedang', 'besar', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan lemah, kondisi baik [skor:11]'],
            [116, 'cukup', 'sedang', 'besar', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal besar, agunan lemah, kondisi cukup [skor:10]'],
            [117, 'cukup', 'sedang', 'besar', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas sedang, modal besar, agunan lemah, kondisi buruk [skor:8]'],
            [118, 'cukup', 'sedang', 'sedang', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal sedang, agunan kuat, kondisi baik [skor:12]'],
            [119, 'cukup', 'sedang', 'sedang', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal sedang, agunan kuat, kondisi cukup [skor:11]'],
            [120, 'cukup', 'sedang', 'sedang', 'tinggi', 'buruk', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal sedang, agunan kuat, kondisi buruk [skor:9]'],
            [121, 'cukup', 'sedang', 'sedang', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal sedang, agunan cukup, kondisi baik [skor:11]'],
            [122, 'cukup', 'sedang', 'sedang', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal sedang, agunan cukup, kondisi cukup [skor:10]'],
            [123, 'cukup', 'sedang', 'sedang', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas sedang, modal sedang, agunan cukup, kondisi buruk [skor:8]'],
            [124, 'cukup', 'sedang', 'sedang', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal sedang, agunan lemah, kondisi baik [skor:10]'],
            [125, 'cukup', 'sedang', 'sedang', 'rendah', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal sedang, agunan lemah, kondisi cukup [skor:9]'],
            [126, 'cukup', 'sedang', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas sedang, modal sedang, agunan lemah, kondisi buruk [skor:7]'],
            [127, 'cukup', 'sedang', 'kecil', 'tinggi', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal kecil, agunan kuat, kondisi baik [skor:11]'],
            [128, 'cukup', 'sedang', 'kecil', 'tinggi', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal kecil, agunan kuat, kondisi cukup [skor:10]'],
            [129, 'cukup', 'sedang', 'kecil', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas sedang, modal kecil, agunan kuat, kondisi buruk [skor:8]'],
            [130, 'cukup', 'sedang', 'kecil', 'sedang', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal kecil, agunan cukup, kondisi baik [skor:10]'],
            [131, 'cukup', 'sedang', 'kecil', 'sedang', 'cukup', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal kecil, agunan cukup, kondisi cukup [skor:9]'],
            [132, 'cukup', 'sedang', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas sedang, modal kecil, agunan cukup, kondisi buruk [skor:7]'],
            [133, 'cukup', 'sedang', 'kecil', 'rendah', 'baik', 'layak', 'linear_naik', 'Karakter cukup, kapasitas sedang, modal kecil, agunan lemah, kondisi baik [skor:9]'],
            [134, 'cukup', 'sedang', 'kecil', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas sedang, modal kecil, agunan lemah, kondisi cukup [skor:8]'],
            [135, 'cukup', 'sedang', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas sedang, modal kecil, agunan lemah, kondisi buruk [skor:6]'],
            [136, 'cukup', 'rendah', 'besar', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan kuat, kondisi baik [skor:11]'],
            [137, 'cukup', 'rendah', 'besar', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan kuat, kondisi cukup [skor:10]'],
            [138, 'cukup', 'rendah', 'besar', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan kuat, kondisi buruk [skor:8]'],
            [139, 'cukup', 'rendah', 'besar', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan cukup, kondisi baik [skor:10]'],
            [140, 'cukup', 'rendah', 'besar', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan cukup, kondisi cukup [skor:9]'],
            [141, 'cukup', 'rendah', 'besar', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan cukup, kondisi buruk [skor:7]'],
            [142, 'cukup', 'rendah', 'besar', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan lemah, kondisi baik [skor:9]'],
            [143, 'cukup', 'rendah', 'besar', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan lemah, kondisi cukup [skor:8]'],
            [144, 'cukup', 'rendah', 'besar', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal besar, agunan lemah, kondisi buruk [skor:6]'],
            [145, 'cukup', 'rendah', 'sedang', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan kuat, kondisi baik [skor:10]'],
            [146, 'cukup', 'rendah', 'sedang', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan kuat, kondisi cukup [skor:9]'],
            [147, 'cukup', 'rendah', 'sedang', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan kuat, kondisi buruk [skor:7]'],
            [148, 'cukup', 'rendah', 'sedang', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan cukup, kondisi baik [skor:9]'],
            [149, 'cukup', 'rendah', 'sedang', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan cukup, kondisi cukup [skor:8]'],
            [150, 'cukup', 'rendah', 'sedang', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan cukup, kondisi buruk [skor:6]'],
            [151, 'cukup', 'rendah', 'sedang', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan lemah, kondisi baik [skor:8]'],
            [152, 'cukup', 'rendah', 'sedang', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan lemah, kondisi cukup [skor:7]'],
            [153, 'cukup', 'rendah', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal sedang, agunan lemah, kondisi buruk [skor:5]'],
            [154, 'cukup', 'rendah', 'kecil', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan kuat, kondisi baik [skor:9]'],
            [155, 'cukup', 'rendah', 'kecil', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan kuat, kondisi cukup [skor:8]'],
            [156, 'cukup', 'rendah', 'kecil', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan kuat, kondisi buruk [skor:6]'],
            [157, 'cukup', 'rendah', 'kecil', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan cukup, kondisi baik [skor:8]'],
            [158, 'cukup', 'rendah', 'kecil', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan cukup, kondisi cukup [skor:7]'],
            [159, 'cukup', 'rendah', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan cukup, kondisi buruk [skor:5]'],
            [160, 'cukup', 'rendah', 'kecil', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan lemah, kondisi baik [skor:7]'],
            [161, 'cukup', 'rendah', 'kecil', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan lemah, kondisi cukup [skor:6]'],
            [162, 'cukup', 'rendah', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter cukup, kapasitas rendah, modal kecil, agunan lemah, kondisi buruk [skor:4]'],
            [163, 'buruk', 'tinggi', 'besar', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan kuat, kondisi baik [skor:12]'],
            [164, 'buruk', 'tinggi', 'besar', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan kuat, kondisi cukup [skor:11]'],
            [165, 'buruk', 'tinggi', 'besar', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan kuat, kondisi buruk [skor:9]'],
            [166, 'buruk', 'tinggi', 'besar', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan cukup, kondisi baik [skor:11]'],
            [167, 'buruk', 'tinggi', 'besar', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan cukup, kondisi cukup [skor:10]'],
            [168, 'buruk', 'tinggi', 'besar', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan cukup, kondisi buruk [skor:8]'],
            [169, 'buruk', 'tinggi', 'besar', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan lemah, kondisi baik [skor:10]'],
            [170, 'buruk', 'tinggi', 'besar', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan lemah, kondisi cukup [skor:9]'],
            [171, 'buruk', 'tinggi', 'besar', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal besar, agunan lemah, kondisi buruk [skor:7]'],
            [172, 'buruk', 'tinggi', 'sedang', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan kuat, kondisi baik [skor:11]'],
            [173, 'buruk', 'tinggi', 'sedang', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan kuat, kondisi cukup [skor:10]'],
            [174, 'buruk', 'tinggi', 'sedang', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan kuat, kondisi buruk [skor:8]'],
            [175, 'buruk', 'tinggi', 'sedang', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan cukup, kondisi baik [skor:10]'],
            [176, 'buruk', 'tinggi', 'sedang', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan cukup, kondisi cukup [skor:9]'],
            [177, 'buruk', 'tinggi', 'sedang', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan cukup, kondisi buruk [skor:7]'],
            [178, 'buruk', 'tinggi', 'sedang', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan lemah, kondisi baik [skor:9]'],
            [179, 'buruk', 'tinggi', 'sedang', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan lemah, kondisi cukup [skor:8]'],
            [180, 'buruk', 'tinggi', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal sedang, agunan lemah, kondisi buruk [skor:6]'],
            [181, 'buruk', 'tinggi', 'kecil', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan kuat, kondisi baik [skor:10]'],
            [182, 'buruk', 'tinggi', 'kecil', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan kuat, kondisi cukup [skor:9]'],
            [183, 'buruk', 'tinggi', 'kecil', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan kuat, kondisi buruk [skor:7]'],
            [184, 'buruk', 'tinggi', 'kecil', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan cukup, kondisi baik [skor:9]'],
            [185, 'buruk', 'tinggi', 'kecil', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan cukup, kondisi cukup [skor:8]'],
            [186, 'buruk', 'tinggi', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan cukup, kondisi buruk [skor:6]'],
            [187, 'buruk', 'tinggi', 'kecil', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan lemah, kondisi baik [skor:8]'],
            [188, 'buruk', 'tinggi', 'kecil', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan lemah, kondisi cukup [skor:7]'],
            [189, 'buruk', 'tinggi', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas tinggi, modal kecil, agunan lemah, kondisi buruk [skor:5]'],
            [190, 'buruk', 'sedang', 'besar', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan kuat, kondisi baik [skor:11]'],
            [191, 'buruk', 'sedang', 'besar', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan kuat, kondisi cukup [skor:10]'],
            [192, 'buruk', 'sedang', 'besar', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan kuat, kondisi buruk [skor:8]'],
            [193, 'buruk', 'sedang', 'besar', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan cukup, kondisi baik [skor:10]'],
            [194, 'buruk', 'sedang', 'besar', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan cukup, kondisi cukup [skor:9]'],
            [195, 'buruk', 'sedang', 'besar', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan cukup, kondisi buruk [skor:7]'],
            [196, 'buruk', 'sedang', 'besar', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan lemah, kondisi baik [skor:9]'],
            [197, 'buruk', 'sedang', 'besar', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan lemah, kondisi cukup [skor:8]'],
            [198, 'buruk', 'sedang', 'besar', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal besar, agunan lemah, kondisi buruk [skor:6]'],
            [199, 'buruk', 'sedang', 'sedang', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan kuat, kondisi baik [skor:10]'],
            [200, 'buruk', 'sedang', 'sedang', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan kuat, kondisi cukup [skor:9]'],
            [201, 'buruk', 'sedang', 'sedang', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan kuat, kondisi buruk [skor:7]'],
            [202, 'buruk', 'sedang', 'sedang', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan cukup, kondisi baik [skor:9]'],
            [203, 'buruk', 'sedang', 'sedang', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan cukup, kondisi cukup [skor:8]'],
            [204, 'buruk', 'sedang', 'sedang', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan cukup, kondisi buruk [skor:6]'],
            [205, 'buruk', 'sedang', 'sedang', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan lemah, kondisi baik [skor:8]'],
            [206, 'buruk', 'sedang', 'sedang', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan lemah, kondisi cukup [skor:7]'],
            [207, 'buruk', 'sedang', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal sedang, agunan lemah, kondisi buruk [skor:5]'],
            [208, 'buruk', 'sedang', 'kecil', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan kuat, kondisi baik [skor:9]'],
            [209, 'buruk', 'sedang', 'kecil', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan kuat, kondisi cukup [skor:8]'],
            [210, 'buruk', 'sedang', 'kecil', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan kuat, kondisi buruk [skor:6]'],
            [211, 'buruk', 'sedang', 'kecil', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan cukup, kondisi baik [skor:8]'],
            [212, 'buruk', 'sedang', 'kecil', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan cukup, kondisi cukup [skor:7]'],
            [213, 'buruk', 'sedang', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan cukup, kondisi buruk [skor:5]'],
            [214, 'buruk', 'sedang', 'kecil', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan lemah, kondisi baik [skor:7]'],
            [215, 'buruk', 'sedang', 'kecil', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan lemah, kondisi cukup [skor:6]'],
            [216, 'buruk', 'sedang', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas sedang, modal kecil, agunan lemah, kondisi buruk [skor:4]'],
            [217, 'buruk', 'rendah', 'besar', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan kuat, kondisi baik [skor:9]'],
            [218, 'buruk', 'rendah', 'besar', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan kuat, kondisi cukup [skor:8]'],
            [219, 'buruk', 'rendah', 'besar', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan kuat, kondisi buruk [skor:6]'],
            [220, 'buruk', 'rendah', 'besar', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan cukup, kondisi baik [skor:8]'],
            [221, 'buruk', 'rendah', 'besar', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan cukup, kondisi cukup [skor:7]'],
            [222, 'buruk', 'rendah', 'besar', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan cukup, kondisi buruk [skor:5]'],
            [223, 'buruk', 'rendah', 'besar', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan lemah, kondisi baik [skor:7]'],
            [224, 'buruk', 'rendah', 'besar', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan lemah, kondisi cukup [skor:6]'],
            [225, 'buruk', 'rendah', 'besar', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal besar, agunan lemah, kondisi buruk [skor:4]'],
            [226, 'buruk', 'rendah', 'sedang', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan kuat, kondisi baik [skor:8]'],
            [227, 'buruk', 'rendah', 'sedang', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan kuat, kondisi cukup [skor:7]'],
            [228, 'buruk', 'rendah', 'sedang', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan kuat, kondisi buruk [skor:5]'],
            [229, 'buruk', 'rendah', 'sedang', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan cukup, kondisi baik [skor:7]'],
            [230, 'buruk', 'rendah', 'sedang', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan cukup, kondisi cukup [skor:6]'],
            [231, 'buruk', 'rendah', 'sedang', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan cukup, kondisi buruk [skor:4]'],
            [232, 'buruk', 'rendah', 'sedang', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan lemah, kondisi baik [skor:6]'],
            [233, 'buruk', 'rendah', 'sedang', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan lemah, kondisi cukup [skor:5]'],
            [234, 'buruk', 'rendah', 'sedang', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal sedang, agunan lemah, kondisi buruk [skor:3]'],
            [235, 'buruk', 'rendah', 'kecil', 'tinggi', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan kuat, kondisi baik [skor:7]'],
            [236, 'buruk', 'rendah', 'kecil', 'tinggi', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan kuat, kondisi cukup [skor:6]'],
            [237, 'buruk', 'rendah', 'kecil', 'tinggi', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan kuat, kondisi buruk [skor:4]'],
            [238, 'buruk', 'rendah', 'kecil', 'sedang', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan cukup, kondisi baik [skor:6]'],
            [239, 'buruk', 'rendah', 'kecil', 'sedang', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan cukup, kondisi cukup [skor:5]'],
            [240, 'buruk', 'rendah', 'kecil', 'sedang', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan cukup, kondisi buruk [skor:3]'],
            [241, 'buruk', 'rendah', 'kecil', 'rendah', 'baik', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan lemah, kondisi baik [skor:5]'],
            [242, 'buruk', 'rendah', 'kecil', 'rendah', 'cukup', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan lemah, kondisi cukup [skor:4]'],
            [243, 'buruk', 'rendah', 'kecil', 'rendah', 'buruk', 'tidak_layak', 'linear_turun', 'Karakter buruk, kapasitas rendah, modal kecil, agunan lemah, kondisi buruk [skor:2]'],
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
                'output_tipe' => $r[7],
                'output_a'    => 0,
                'output_b'    => 100,
                'deskripsi'   => $r[8],
                'is_active'   => true,
            ]);
        }

        $this->command->info('');
        $this->command->info('✅ Seeder selesai:');
        $this->command->info('   • 4 akun pengguna (login pakai NIK)');
        $this->command->info('   • 15 parameter fuzzy 5C');
        $this->command->info('   • 243 rule fuzzy (3^5 kombinasi lengkap)');
        $this->command->table(
            ['Role', 'NIK Login', 'Password'],
            [
                ['Admin',         'ADM001',  'admin123'],
                ['Kredit Analis', 'KA001',   'analis123'],
                ['Kepala Cabang', 'KC001',   'kc12345678'],
                ['Marketing',     'MKT001',  'mkt12345678'],
            ]
        );
    }
}
