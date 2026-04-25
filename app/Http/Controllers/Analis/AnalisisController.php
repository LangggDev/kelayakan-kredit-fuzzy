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

        $jumlahDitolak = HasilAnalisis::where('user_id', auth()->id())
            ->where('status_approval', 'ditolak')
            ->count();

        return view('analis.analisis.index', compact('riwayat', 'jumlahDitolak'));
    }

    public function create()
    {
        $nasabahList = CalonNasabah::orderBy('nama')->get();
        return view('analis.analisis.create', compact('nasabahList'));
    }

    public function revisi(HasilAnalisis $analisis)
    {
        if ($analisis->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($analisis->status_approval !== 'ditolak') {
            return redirect()->route('analis.analisis.show', $analisis->id)
                ->with('error', 'Hanya analisis yang ditolak yang dapat direvisi.');
        }

        $analisis->load(['calonNasabah']);
        $nasabahList = CalonNasabah::orderBy('nama')->get();

        return view('analis.analisis.revisi', compact('analisis', 'nasabahList'));
    }

    public function store(Request $request)
    {
        // Validasi umum
        $request->validate([
            'mode'            => 'required|in:baru,existing',
            'skor_kredit'     => 'required|numeric|min:0|max:100',
            'penghasilan'     => 'required|numeric|min:1',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'jangka_waktu'    => 'required|integer|min:1|max:360',
            'aset_bersih'     => 'required|numeric',
            'nilai_agunan'    => 'required|numeric|min:0',
            'kondisi_ekonomi' => 'required|numeric|min:0|max:100',
            'catatan'         => 'nullable|string',
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

    public function storeRevisi(Request $request, HasilAnalisis $analisis)
    {
        if ($analisis->user_id !== auth()->id()) {
            abort(403);
        }
        if ($analisis->status_approval !== 'ditolak') {
            return redirect()->route('analis.analisis.show', $analisis->id)
                ->with('error', 'Hanya analisis yang ditolak yang dapat direvisi.');
        }

        $request->validate([
            'skor_kredit'     => 'required|numeric|min:0|max:100',
            'penghasilan'     => 'required|numeric|min:1',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'jangka_waktu'    => 'required|integer|min:1|max:360',
            'aset_bersih'     => 'required|numeric',
            'nilai_agunan'    => 'required|numeric|min:0',
            'kondisi_ekonomi' => 'required|numeric|min:0|max:100',
            'catatan'         => 'nullable|string',
        ]);

        $nasabah = $analisis->calonNasabah;

        $analisis->update(['status_approval' => 'direvisi']);

        $hasilBaru = $this->prosesAndSimpan($request, $nasabah, $analisis->id);

        return $hasilBaru;
    }

    private function prosesAndSimpan(Request $request, CalonNasabah $nasabah, ?int $revisiDariId = null)
    {
        // Hitung rasio otomatis
        $rasioCicilan = FuzzyTsukamoto::hitungRasioCicilan(
            (float) $request->jumlah_pinjaman,
            (int)   $request->jangka_waktu,
            (float) $request->penghasilan,
            12.0
        );

        $ltvRatio = FuzzyTsukamoto::hitungLTV(
            (float) $request->jumlah_pinjaman,
            (float) $request->nilai_agunan
        );

        // Proses Fuzzy Tsukamoto
        $fuzzy = new FuzzyTsukamoto();
        $hasil = $fuzzy->proses(
            (float) $request->skor_kredit,
            $rasioCicilan,
            (float) $request->aset_bersih,
            $ltvRatio,
            (float) $request->kondisi_ekonomi
        );

        $catatan = $request->catatan;
        if ($revisiDariId) {
            $keterangan = "Analisis ulang dari #$revisiDariId.";
            $catatan = $catatan ? "$keterangan $catatan" : $keterangan;
        }

        $hasilAnalisis = HasilAnalisis::create([
            'user_id'              => auth()->user()->id,
            'calon_nasabah_id'     => $nasabah->id,
            'skor_kredit'          => $request->skor_kredit,
            'penghasilan'          => $request->penghasilan,
            'rasio_cicilan'        => $rasioCicilan,
            'aset_bersih'          => $request->aset_bersih,
            'nilai_agunan'         => $request->nilai_agunan,
            'jumlah_pinjaman'      => $request->jumlah_pinjaman,
            'jangka_waktu'         => $request->jangka_waktu,
            'kondisi_ekonomi'      => $request->kondisi_ekonomi,
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
            'status_approval'      => 'menunggu',
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
