<?php

namespace App\Services;

use App\Models\Parameter;
use App\Models\RuleFuzzy;

class FuzzyTsukamoto
{
    private array $parameters;
    private array $rules;

    public function __construct()
    {
        $this->parameters = Parameter::where('is_active', true)
            ->get()
            ->groupBy('nama_parameter')
            ->toArray();

        $this->rules = RuleFuzzy::where('is_active', true)
            ->orderBy('nomor_rule')
            ->get()
            ->toArray();
    }


    //  Hitung derajat keanggotaan berdasarkan tipe fungsi
    public function hitungKeanggotaan(float $nilai, string $tipe, float $a, float $b, ?float $c = null): float
    {
        switch ($tipe) {
            case 'linear_naik':
                if ($nilai <= $a)
                    return 0.0;
                if ($nilai >= $b)
                    return 1.0;
                return ($nilai - $a) / ($b - $a);

            case 'linear_turun':
                if ($nilai <= $a)
                    return 1.0;
                if ($nilai >= $b)
                    return 0.0;
                return ($b - $nilai) / ($b - $a);

            case 'segitiga':
                if ($nilai <= $a || $nilai >= $c)
                    return 0.0;
                if ($nilai == $b)
                    return 1.0;
                if ($nilai < $b)
                    return ($nilai - $a) / ($b - $a);
                return ($c - $nilai) / ($c - $b);

            case 'singleton':
                return $nilai == $a ? 1.0 : 0.0;

            default:
                return 0.0;
        }
    }


    // Fuzzifikasi semua input
    public function fuzzifikasi(
        float $skorKreditSlik,
        float $capacity,
        float $capital,
        float $collateral,
        float $condition
    ): array {
        $inputs = [
            'skor_kredit' => $skorKreditSlik,
            'rasio_cicilan' => $capacity,
            'aset_bersih' => $capital,
            'ltv_ratio' => $collateral,
            'kondisi_ekonomi' => $condition,
        ];

        $result = [];
        foreach ($inputs as $paramName => $nilai) {
            if (!isset($this->parameters[$paramName]))
                continue;
            $result[$paramName] = [];
            foreach ($this->parameters[$paramName] as $param) {
                $mu = $this->hitungKeanggotaan(
                    $nilai,
                    $param['tipe_fungsi'],
                    (float) $param['a'],
                    (float) $param['b'],
                    isset($param['c']) && $param['c'] !== null ? (float) $param['c'] : null
                );
                $result[$paramName][$param['himpunan']] = round($mu, 4);
            }
        }
        return $result;
    }


    //  Ambil nilai μ
    private function getMu(array $fuzz, string $param, string $himpunan): float
    {
        if ($himpunan === 'any')
            return 1.0;
        // Map nama parameter ke key fuzzifikasi
        $keyMap = [
            'character' => 'skor_kredit',
            'capacity' => 'rasio_cicilan',
            'capital' => 'aset_bersih',
            'collateral' => 'ltv_ratio',
            'condition' => 'kondisi_ekonomi',
        ];
        $key = $keyMap[$param] ?? $param;
        return $fuzz[$key][$himpunan] ?? 0.0;
    }


    // Defuzzifikasi Tsukamoto

    private function hitungZ(float $alpha, float $a, float $b, string $tipe): float
    {
        if ($tipe === 'linear_naik') {
            return $a + $alpha * ($b - $a);
        }
        return $b - $alpha * ($b - $a);
    }


    // Proses lengkap Fuzzy Tsukamoto
    public function proses(
        float $skorKreditSlik,
        float $capacity,
        float $capital,
        float $collateral,
        float $condition
    ): array {
        // Tahap 1: Fuzzifikasi
        $fuzz = $this->fuzzifikasi($skorKreditSlik, $capacity, $capital, $collateral, $condition);

        // Tahap 2 & 3: Evaluasi rule & inferensi
        $detailRule = [];
        $sumAlphaZ = 0;
        $sumAlpha = 0;

        foreach ($this->rules as $rule) {
            $muChar = $this->getMu($fuzz, 'character', $rule['character']);
            $muCap = $this->getMu($fuzz, 'capacity', $rule['capacity']);
            $muCapit = $this->getMu($fuzz, 'capital', $rule['capital']);
            $muColl = $this->getMu($fuzz, 'collateral', $rule['collateral']);
            $muCond = $this->getMu($fuzz, 'condition', $rule['condition']);

            $alpha = min($muChar, $muCap, $muCapit, $muColl, $muCond);

            if ($alpha > 0) {
                $z = $this->hitungZ($alpha, (float) $rule['output_a'], (float) $rule['output_b'], $rule['output_tipe']);
                $sumAlphaZ += $alpha * $z;
                $sumAlpha += $alpha;

                $detailRule[] = [
                    'nomor_rule' => $rule['nomor_rule'],
                    'deskripsi' => $rule['deskripsi'],
                    'kelayakan' => $rule['kelayakan'],
                    'mu_character' => round($muChar, 4),
                    'mu_capacity' => round($muCap, 4),
                    'mu_capital' => round($muCapit, 4),
                    'mu_collateral' => round($muColl, 4),
                    'mu_condition' => round($muCond, 4),
                    'alpha' => round($alpha, 4),
                    'z' => round($z, 4),
                    'alpha_z' => round($alpha * $z, 4),
                ];
            }
        }

        // Tahap 4: Defuzzifikasi weighted average
        $nilaiDefuzz = $sumAlpha > 0 ? ($sumAlphaZ / $sumAlpha) : 0;
        $keputusan = $nilaiDefuzz > 70 ? 'Layak' : 'Tidak Layak';

        // Hitung skor per komponen 5C (0-100)
        // Gunakan nilai input langsung agar sama dengan skor 0-100 di form input
        $skorChar = $skorKreditSlik; // Already mapped to 0-100 by controller
        $skorCap = $capacity;
        $skorCapit = $capital;
        $skorColl = $collateral;
        $skorCond = $condition;

        return [
            'fuzzifikasi' => $fuzz,
            'detail_rule' => $detailRule,
            'sum_alpha_z' => round($sumAlphaZ, 4),
            'sum_alpha' => round($sumAlpha, 4),
            'nilai_defuzzifikasi' => round($nilaiDefuzz, 4),
            'persentase_kelayakan' => round($nilaiDefuzz, 2),
            'keputusan' => $keputusan,
            'skor_character' => $skorChar,
            'skor_capacity' => $skorCap,
            'skor_capital' => $skorCapit,
            'skor_collateral' => $skorColl,
            'skor_condition' => $skorCond,
        ];
    }


    // Hitung skor komponen 0-100 menggunakan weighted average per himpunan
    private function hitungSkorKomponen(array $muValues, array $bobotHimpunan): float
    {
        $sumMuBobot = 0;
        $sumMu = 0;
        foreach ($muValues as $himpunan => $mu) {
            $bobot = $bobotHimpunan[$himpunan] ?? 50;
            $sumMuBobot += $mu * $bobot;
            $sumMu += $mu;
        }
        return $sumMu > 0 ? round($sumMuBobot / $sumMu, 2) : 0;
    }

    // Hitung rasio cicilan per bulan terhadap penghasilan

    public static function hitungRasioCicilan(float $pinjaman, int $jangkaWaktu, float $penghasilan, float $bungaPerTahun = 12.0): float
    {
        if ($penghasilan <= 0 || $jangkaWaktu <= 0)
            return 100;
        $bungaPerBulan = ($bungaPerTahun / 100) / 12;
        if ($bungaPerBulan > 0) {
            $cicilan = $pinjaman * ($bungaPerBulan * pow(1 + $bungaPerBulan, $jangkaWaktu))
                / (pow(1 + $bungaPerBulan, $jangkaWaktu) - 1);
        } else {
            $cicilan = $pinjaman / $jangkaWaktu;
        }
        return round(($cicilan / $penghasilan) * 100, 2);
    }


    // Hitung LTV Ratio = (Pinjaman / Nilai Agunan) × 100
    public static function hitungLTV(float $pinjaman, float $nilaiAgunan): float
    {
        if ($nilaiAgunan <= 0)
            return 150;
        return round(($pinjaman / $nilaiAgunan) * 100, 2);
    }
}
