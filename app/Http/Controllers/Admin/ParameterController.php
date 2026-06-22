<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    public static function meta5C(): array
    {
        return [
            'character' => [
                'label' => 'Character — Karakter Nasabah',
                'kode' => 'C1',
                'color' => 'blue',
                'icon' => 'fa-id-card',
                'satuan' => 'skor (0–100)',
                'nama_param' => 'skor_kredit',
                'deskripsi' => 'Riwayat kredit nasabah berdasarkan BI Checking / SLIK OJK.',
                'himpunan' => ['buruk', 'cukup', 'baik'],
                'panduan' => 'S1 = Excelent, Very Good, Good  | S2 = Medium, Bad 1 | S3 = Bad 2, Worst',
            ],
            'capacity' => [
                'label' => 'Capacity — Kemampuan Membayar',
                'kode' => 'C2',
                'color' => 'green',
                'icon' => 'fa-coins',
                'satuan' => '% (rasio cicilan/penghasilan)',
                'nama_param' => 'rasio_cicilan',
                'deskripsi' => 'DSCR: Rasio cicilan bulanan terhadap penghasilan. Makin kecil makin baik.',
                'himpunan' => ['tinggi', 'sedang', 'rendah'],
                'panduan' => '<50% = Sangat Layak | 50–80% = Layak | >80% = Tidak Layak',
            ],
            'capital' => [
                'label' => 'Capital — Modal / Kekayaan Bersih',
                'kode' => 'C3',
                'color' => 'amber',
                'icon' => 'fa-building-columns',
                'satuan' => 'Rp (aset bersih)',
                'nama_param' => 'aset_bersih',
                'deskripsi' => 'Total aset dikurangi total kewajiban/hutang nasabah.',
                'himpunan' => ['kecil', 'sedang', 'besar'],
                'panduan' => '< 50 jt = Kecil | 25–200 jt = Sedang | > 150 jt = Besar',
            ],
            'collateral' => [
                'label' => 'Collateral — Agunan / Jaminan',
                'kode' => 'C4',
                'color' => 'purple',
                'icon' => 'fa-shield-halved',
                'satuan' => '% (LTV Ratio)',
                'nama_param' => 'ltv_ratio',
                'deskripsi' => 'LTV = Pinjaman ÷ Nilai Agunan × 100%. Makin kecil LTV makin kuat agunan.',
                'himpunan' => ['rendah', 'sedang', 'tinggi'],
                'panduan' => '> 80% = Rendah (lemah) | 60–110% = Sedang | < 70% = Tinggi (kuat)',
            ],
            'condition' => [
                'label' => 'Condition — Kondisi Ekonomi',
                'kode' => 'C5',
                'color' => 'rose',
                'icon' => 'fa-chart-line',
                'satuan' => 'skor (0–100)',
                'nama_param' => 'kondisi_ekonomi',
                'deskripsi' => 'Penilaian kondisi ekonomi makro dan sektor usaha nasabah.',
                'himpunan' => ['buruk', 'cukup', 'baik'],
                'panduan' => '0–40 = Buruk | 41–60 = Cukup | 61–100 = Baik',
            ],
        ];
    }

    public function index()
    {
        $meta = self::meta5C();
        $parameters = Parameter::where('is_active', true)
            ->orderBy('kategori_5c')
            ->orderBy('himpunan')
            ->get()
            ->groupBy('kategori_5c');

        return view('admin.parameter.index', compact('parameters', 'meta'));
    }

    public function create()
    {
        $meta = self::meta5C();
        return view('admin.parameter.create', compact('meta'));
    }

    public function store(Request $request)
    {
        $meta = self::meta5C();

        $request->validate([
            'kategori_5c' => 'required|in:' . implode(',', array_keys($meta)),
            'himpunan' => 'required|string|max:50',
            'tipe_fungsi' => 'required|in:linear_naik,linear_turun,segitiga',
            'a' => 'required|numeric',
            'b' => 'required|numeric',
            'c' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = $request->kategori_5c;
        $metaKat = $meta[$kategori];

        Parameter::create([
            'kategori_5c' => $kategori,
            'nama_parameter' => $metaKat['nama_param'],
            'kode' => $metaKat['kode'],
            'himpunan' => strtolower(trim($request->himpunan)),
            'tipe_fungsi' => $request->tipe_fungsi,
            'a' => $request->a,
            'b' => $request->b,
            'c' => $request->c,
            'd' => null,
            'satuan' => $metaKat['satuan'],
            'keterangan' => $request->keterangan,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.parameter.index')
            ->with('success', 'Parameter ' . $metaKat['label'] . ' berhasil ditambahkan.');
    }

    public function edit(Parameter $parameter)
    {
        $meta = self::meta5C();
        return view('admin.parameter.edit', compact('parameter', 'meta'));
    }

    public function update(Request $request, Parameter $parameter)
    {
        $meta = self::meta5C();

        $request->validate([
            'kategori_5c' => 'required|in:' . implode(',', array_keys($meta)),
            'himpunan' => 'required|string|max:50',
            'tipe_fungsi' => 'required|in:linear_naik,linear_turun,segitiga',
            'a' => 'required|numeric',
            'b' => 'required|numeric',
            'c' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = $request->kategori_5c;
        $metaKat = $meta[$kategori];

        $parameter->update([
            'kategori_5c' => $kategori,
            'nama_parameter' => $metaKat['nama_param'],
            'kode' => $metaKat['kode'],
            'himpunan' => strtolower(trim($request->himpunan)),
            'tipe_fungsi' => $request->tipe_fungsi,
            'a' => $request->a,
            'b' => $request->b,
            'c' => $request->c,
            'satuan' => $metaKat['satuan'],
            'keterangan' => $request->keterangan,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.parameter.index')
            ->with('success', 'Parameter berhasil diperbarui.');
    }

    public function destroy(Parameter $parameter)
    {
        $parameter->delete();
        return redirect()->route('admin.parameter.index')
            ->with('success', 'Parameter berhasil dihapus.');
    }
}
