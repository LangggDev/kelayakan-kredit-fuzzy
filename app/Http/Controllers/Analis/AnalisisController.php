<?php

namespace App\Http\Controllers\Analis;

use App\Http\Controllers\Controller;
use App\Models\CalonNasabah;
use App\Models\HasilAnalisis;
use App\Services\FuzzyTsukamoto;
use Illuminate\Http\Request;

class AnalisisController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilAnalisis::with(['calonNasabah'])
            ->where('user_id', auth()->id())
            ->latest();

        if ($request->filled('search')) {
            $query->whereHas('calonNasabah', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nik',  'like', "%{$request->search}%");
            });
        }

        if ($request->filled('keputusan')) {
            $query->where('keputusan', $request->keputusan);
        }

        if ($request->filled('status')) {
            $query->where('status_approval', $request->status);
        }

        $riwayat = $query->paginate(10)->withQueryString();

        return view('analis.analisis.index', compact('riwayat'));
    }

    public function create()
    {
        $nasabahList = CalonNasabah::orderBy('nama')->get();
        $settings = \App\Models\SettingKonversi::all()->keyBy('kriteria');
        return view('analis.analisis.create', compact('nasabahList', 'settings'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'mode'             => 'required|in:baru,existing',
            'skor_kredit_slik' => 'required|numeric|in:1,2,3',
            'capacity'         => 'required|numeric|min:0|max:100',
            'capital'          => 'required|numeric|min:0|max:100',
            'collateral'       => 'required|numeric|min:0|max:100',
            'condition'        => 'required|numeric|min:0|max:100',
            'penghasilan'      => 'nullable|numeric',
            'jumlah_pinjaman'  => 'nullable|numeric',
            'jangka_waktu'     => 'nullable|numeric',
            'catatan'          => 'nullable|string',
        ]);

        // Validasi mode
        if ($request->mode === 'baru') {
            $request->validate([
                'nama' => 'required|string|max:255',
                'nik'  => 'required|string|max:20|unique:calon_nasabah,nik',
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'nik.required'  => 'NIK wajib diisi.',
                'nik.unique'    => 'NIK sudah terdaftar. Gunakan mode Nasabah Terdaftar.',
            ]);

            $nasabah = CalonNasabah::create([
                'nama'          => $request->nama,
                'nik'           => $request->nik,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat'        => $request->alamat,
                'telepon'       => $request->telepon,
                'pekerjaan'     => $request->pekerjaan,
                'nama_usaha'    => $request->nama_usaha,
            ]);
        } else {
            $request->validate([
                'calon_nasabah_id' => 'required|exists:calon_nasabah,id',
            ], [
                'calon_nasabah_id.required' => 'Pilih nasabah yang sudah terdaftar.',
            ]);
            $nasabah = CalonNasabah::findOrFail($request->calon_nasabah_id);
        }

        return $this->prosesAndSimpan($request, $nasabah);
    }

    private function prosesAndSimpan(Request $request, CalonNasabah $nasabah, ?int $revisiDariId = null)
    {
        // Ambil Setting Konversi dari Database
        $settings = \App\Models\SettingKonversi::all()->keyBy('kriteria');
        $capSL = $settings['capacity']->batas_sangat_layak ?? 30;
        $capTL = $settings['capacity']->batas_tidak_layak ?? 70;
        
        $capitSL = $settings['capital']->batas_sangat_layak ?? 200;
        $capitTL = $settings['capital']->batas_tidak_layak ?? 0;
        
        $colSL = $settings['collateral']->batas_sangat_layak ?? 70;
        $colTL = $settings['collateral']->batas_tidak_layak ?? 110;

        // Hitung ulang rasio untuk mendapatkan skor fuzzy 0-100 secara valid di backend
        $pinjaman = (float) $request->jumlah_pinjaman;
        $penghasilan = (float) $request->penghasilan;
        $jangka = (int) $request->jangka_waktu;
        $asetBersih = (float) $request->aset_bersih ?? (($request->total_aset ?? 0) - ($request->total_hutang ?? 0));
        $agunan = (float) $request->nilai_agunan;

        // Hitung C2 Capacity
        $capacityScore = 0;
        if ($pinjaman > 0 && $penghasilan > 0 && $jangka > 0) {
            $r = 0.12 / 12;
            $cicilan = $pinjaman * ($r * pow(1+$r, $jangka)) / (pow(1+$r, $jangka) - 1);
            $rasio = ($cicilan / $penghasilan) * 100;
            
            if ($capSL < $capTL) {
                if ($rasio <= $capSL) $capacityScore = 100;
                else if ($rasio >= $capTL) $capacityScore = 0;
                else $capacityScore = 100 - (($rasio - $capSL) / ($capTL - $capSL)) * 100;
            } else {
                if ($rasio >= $capSL) $capacityScore = 100;
                else if ($rasio <= $capTL) $capacityScore = 0;
                else $capacityScore = (($rasio - $capTL) / ($capSL - $capTL)) * 100;
            }
        }

        // Hitung C3 Capital
        $capitalScore = 0;
        if ($pinjaman > 0) {
            $rasioAset = ($asetBersih / $pinjaman) * 100;
            
            if ($capitSL > $capitTL) {
                if ($rasioAset >= $capitSL) $capitalScore = 100;
                else if ($rasioAset <= $capitTL) $capitalScore = 0;
                else $capitalScore = (($rasioAset - $capitTL) / ($capitSL - $capitTL)) * 100;
            } else {
                if ($rasioAset <= $capitSL) $capitalScore = 100;
                else if ($rasioAset >= $capitTL) $capitalScore = 0;
                else $capitalScore = 100 - (($rasioAset - $capitSL) / ($capitTL - $capitSL)) * 100;
            }
        }

        // Hitung C4 Collateral
        $collateralScore = 0;
        if ($agunan > 0 && $pinjaman > 0) {
            $ltv = ($pinjaman / $agunan) * 100;
            
            if ($colSL < $colTL) {
                if ($ltv <= $colSL) $collateralScore = 100;
                else if ($ltv >= $colTL) $collateralScore = 0;
                else $collateralScore = 100 - (($ltv - $colSL) / ($colTL - $colSL)) * 100;
            } else {
                if ($ltv >= $colSL) $collateralScore = 100;
                else if ($ltv <= $colTL) $collateralScore = 0;
                else $collateralScore = (($ltv - $colTL) / ($colSL - $colTL)) * 100;
            }
        }

        // Map Slik 1, 2, 3 to Fuzzy Input (100, 55, 0)
        $slikType = (int) $request->skor_kredit_slik;
        $skorSlikMapped = 0;
        if ($slikType === 1) $skorSlikMapped = 100; // Baik
        elseif ($slikType === 2) $skorSlikMapped = 55; // Cukup
        elseif ($slikType === 3) $skorSlikMapped = 0; // Buruk

        // Proses Fuzzy Tsukamoto
        $fuzzy = new FuzzyTsukamoto();
        $hasil = $fuzzy->proses(
            $skorSlikMapped,
            round($capacityScore),
            round($capitalScore),
            round($collateralScore),
            (float) $request->condition
        );

        // Jika Slik = 3 (Bad/Worst), otomatis hasil tidak layak
        if ((int) $request->skor_kredit_slik === 3) {
            $hasil['keputusan'] = 'Tidak Layak';
            $hasil['persentase_kelayakan'] = 0;
            $hasil['nilai_defuzzifikasi'] = 0;
            $catatanOtomatis = 'Otomatis Tidak Layak karena Tipe SLIK adalah Bad/Worst.';
        }

        $catatan = $request->catatan;
        if (isset($catatanOtomatis)) {
            $catatan = $catatan ? $catatanOtomatis . "\n" . $catatan : $catatanOtomatis;
        }
        if ($revisiDariId) {
            $keterangan = "Analisis ulang dari #$revisiDariId.";
            $catatan = $catatan ? "$keterangan\n$catatan" : $keterangan;
        }

        $hasilAnalisis = HasilAnalisis::create([
            'user_id'              => auth()->user()->id,
            'calon_nasabah_id'     => $nasabah->id,
            'skor_kredit'          => $request->skor_kredit_slik, // Simpan Slik 1/2/3
            'penghasilan'          => $request->penghasilan ?? 0,
            'rasio_cicilan'        => isset($rasio) ? round($rasio, 4) : 0, // Simpan rasio cicilan asli (%)
            'aset_bersih'          => $asetBersih, // Simpan aset bersih asli (Rp)
            'nilai_agunan'         => $agunan, // Simpan nilai agunan asli (Rp)
            'jumlah_pinjaman'      => $request->jumlah_pinjaman ?? 0,
            'jangka_waktu'         => $request->jangka_waktu ?? 0,
            'kondisi_ekonomi'      => $request->condition, // Simpan condition
            'nilai_fuzzifikasi'    => $hasil['fuzzifikasi'],
            'detail_rule'          => $hasil['detail_rule'],
            'nilai_defuzzifikasi'  => $hasil['nilai_defuzzifikasi'],
            'keputusan'            => $hasil['keputusan'],
            'persentase_kelayakan' => $hasil['persentase_kelayakan'],
            'skor_character'       => $hasil['skor_character'],
            'skor_capacity'        => $hasil['skor_capacity'],
            'skor_capital'         => $hasil['skor_capital'],
            'skor_collateral'      => $hasil['skor_collateral'],
            'skor_condition'       => $hasil['skor_condition'],
            'catatan'              => $catatan,
            'status_approval'      => $hasil['keputusan'] === 'Layak' ? 'menunggu' : 'tidak_layak',
        ]);

        $pesan = $revisiDariId
            ? 'Analisis ulang berhasil dikirim dan menunggu persetujuan Kepala Cabang.'
            : 'Analisis kelayakan kredit berhasil dilakukan.';

        return redirect()->route('analis.analisis.show', $hasilAnalisis->id)
            ->with('success', $pesan);
    }

    public function show(HasilAnalisis $analisis)
    {
        if ($analisis->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $analisis->load(['calonNasabah', 'user', 'approvedBy']);
        return view('analis.analisis.show', compact('analisis'));
    }

    public function exportPdf(HasilAnalisis $analisis)
    {
        if ($analisis->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $analisis->load(['calonNasabah', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('analis.analisis.pdf', compact('analisis'))
            ->setPaper('a4', 'portrait');
        $filename = 'analisis-5c-' . $analisis->calonNasabah->nik . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }
}
