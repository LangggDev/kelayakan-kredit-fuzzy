@extends('layouts.app')
@section('title', 'Detail Analisis')
@section('page-title', 'Detail Hasil Analisis')
@section('page-subtitle', 'Laporan kelayakan kredit — ' . ($analisis->calonNasabah?->nama ?? 'Nasabah Terhapus'))

@section('content')

    <!-- BANNER TIDAK LAYAK -->
    @if($analisis->status_approval === 'tidak_layak')
        <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50 mb-6">
            <i class="fa-solid fa-ban text-slate-400 text-xl flex-shrink-0"></i>
            <div>
                <p class="font-bold text-slate-600 text-sm">Hasil Analisis: Tidak Layak</p>
                <p class="text-xs text-slate-400">Analisis ini tercatat otomatis sebagai Tidak Layak. Tidak memerlukan
                    persetujuan Kepala Cabang.</p>
            </div>
        </div>
    @endif

    <!-- BANNER DISETUJUI -->
    @if($analisis->status_approval === 'disetujui')
        <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-green-200 bg-green-50 mb-6">
            <i class="fa-solid fa-circle-check text-green-600 text-xl flex-shrink-0"></i>
            <div class="flex-1">
                <p class="font-bold text-green-800 text-sm">Disetujui oleh Kepala Cabang</p>
                <p class="text-xs text-green-600">
                    {{ $analisis->approvedBy?->name }} &bull; {{ $analisis->approved_at?->format('d M Y, H:i') }}
                    @if($analisis->catatan_approval)
                        &bull; "{{ $analisis->catatan_approval }}"
                    @endif
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold badge-disetujui border">✅ Disetujui</span>
        </div>
    @endif

    <!-- BANNER MENUNGGU -->
    @if($analisis->status_approval === 'menunggu')
        <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-amber-200 bg-amber-50 mb-6">
            <i class="fa-solid fa-clock text-amber-500 text-xl flex-shrink-0"></i>
            <div>
                <p class="font-bold text-amber-800 text-sm">Menunggu Tanda Tangan Kepala Cabang</p>
                <p class="text-xs text-amber-600">Analisis ini sedang dalam antrian tanda tangan Kepala Cabang.</p>
            </div>
        </div>
    @endif

    <!-- Result Header Banner -->
    <div
        class="rounded-2xl overflow-hidden {{ $analisis->keputusan === 'Layak' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-rose-600' }} p-6 text-white mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <i
                        class="fa-solid {{ $analisis->keputusan === 'Layak' ? 'fa-circle-check' : 'fa-circle-xmark' }} text-3xl"></i>
                </div>
                <div>
                    <p class="text-white/70 text-sm">Keputusan Kelayakan Kredit</p>
                    <h2 class="text-3xl font-bold">{{ $analisis->keputusan }}</h2>
                    <p class="text-white/70 text-sm mt-1">Skor: <span
                            class="text-white font-semibold font-mono">{{ number_format($analisis->nilai_defuzzifikasi, 4) }}</span>
                        &mdash; {{ number_format($analisis->persentase_kelayakan, 2) }}%</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('analis.analisis.index') }}"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-sm font-medium transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('analis.analisis.pdf', $analisis->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-sm font-medium transition-colors">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2"><i
                    class="fa-solid fa-user text-indigo-500"></i> Data Calon Debitur</h3>
            <dl class="space-y-2">
                @foreach(['Nama' => $analisis->calonNasabah?->nama ?? 'Data Terhapus', 'NIK' => $analisis->calonNasabah?->nik ?? '-', 'Pekerjaan' => $analisis->calonNasabah?->pekerjaan ?? '-', 'Telepon' => $analisis->calonNasabah?->telepon ?? '-'] as $lbl => $val)
                    <div class="flex justify-between py-2 border-b border-slate-50 last:border-0">
                        <dt class="text-sm text-slate-500">{{ $lbl }}</dt>
                        <dd class="text-sm font-medium text-slate-800 text-right">{{ $val }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
        <div class="card p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2"><i
                    class="fa-solid fa-sliders text-indigo-500"></i> Parameter Input</h3>
            <dl class="space-y-2">
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">Penghasilan/Bulan</dt>
                    <dd class="text-sm font-semibold">Rp {{ number_format($analisis->penghasilan, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">Jumlah Pinjaman</dt>
                    <dd class="text-sm font-semibold">Rp {{ number_format($analisis->jumlah_pinjaman, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">Rasio Cicilan</dt>
                    <dd class="text-sm font-semibold">{{ number_format($analisis->rasio_cicilan, 2) }}%</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">Jangka Waktu</dt>
                    <dd class="text-sm font-semibold">{{ $analisis->jangka_waktu }} bulan</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">Analis</dt>
                    <dd class="text-sm font-semibold">{{ $analisis->user?->name ?? 'Analis Terhapus' }}</dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-sm text-slate-500">Tanggal</dt>
                    <dd class="text-sm font-semibold">
                        {{ $analisis->created_at ? $analisis->created_at->format('d M Y, H:i') : '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if(!empty($analisis->nilai_fuzzifikasi))
        <div class="card p-6 mb-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2"><i
                    class="fa-solid fa-wave-square text-indigo-500"></i> Fuzzifikasi</h3>
            @php 
                $pm = ['skor_kredit' => ['Skor Kredit (Character)', 'bg-blue-50', 'bg-blue-500', 'text-blue-700'], 'rasio_cicilan' => ['Rasio Cicilan (Capacity)', 'bg-green-50', 'bg-green-500', 'text-green-700'], 'aset_bersih' => ['Aset Bersih (Capital)', 'bg-orange-50', 'bg-orange-500', 'text-orange-700'], 'ltv_ratio' => ['LTV Ratio (Collateral)', 'bg-purple-50', 'bg-purple-500', 'text-purple-700'], 'kondisi_ekonomi' => ['Kondisi Ekonomi (Condition)', 'bg-red-50', 'bg-red-500', 'text-red-700']]; 
                $himpunanMap = [
                    'skor_kredit'     => ['buruk'=>'S3 (tipe Slik 3)','cukup'=>'S2 (tipe Slik 2)','baik'=>'S1 (tipe Slik 1)'],
                    'rasio_cicilan'   => ['tinggi'=>'Sangat Layak','sedang'=>'Layak','rendah'=>'Tidak Layak'],
                    'aset_bersih'     => ['kecil'=>'Tidak Layak','sedang'=>'Layak','besar'=>'Sangat Layak'],
                    'ltv_ratio'       => ['rendah'=>'Tidak Layak','sedang'=>'Layak','tinggi'=>'Sangat Layak'],
                    'kondisi_ekonomi' => ['buruk'=>'Tidak Layak','cukup'=>'Layak','baik'=>'Sangat Layak']
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($analisis->nilai_fuzzifikasi as $p => $vals)
                    @if(isset($pm[$p])) @php [$lbl, $bg, $bar, $txt] = $pm[$p]; @endphp
                        <div class="{{ $bg }} rounded-xl p-4">
                            <p class="font-semibold text-slate-700 text-sm mb-3">{{ $lbl }}</p>
                            @foreach($vals as $h => $mu)
                                <div class="mb-2">
                                    <div class="flex justify-between mb-1"><span
                                            class="text-xs text-slate-600 capitalize">{{ $himpunanMap[$p][$h] ?? $h }}</span><span
                                            class="text-xs font-mono font-semibold {{ $txt }}">{{ number_format($mu, 4) }}</span></div>
                                    <div class="h-2 bg-white/60 rounded-full">
                                        <div class="{{ $bar }} h-full rounded-full" style="width:{{ $mu * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($analisis->detail_rule))
        <div class="card p-6 mb-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2"><i
                    class="fa-solid fa-code-branch text-indigo-500"></i> Inferensi Rule Fuzzy</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Rule</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">μ Char</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">μ Capy</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">μ Capit</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">μ Collat</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">μ Condit</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">α</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">z</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">α×z</th>
                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Output</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($analisis->detail_rule as $r)
                            <tr class="table-row">
                                <td class="px-3 py-3 text-center"><span
                                        class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center mx-auto">{{ $r['nomor_rule'] }}</span>
                                </td>
                                <td class="px-3 py-3 text-center font-mono text-xs">{{ number_format(($r['mu_character'] ?? 0), 4) }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono text-xs">{{ number_format(($r['mu_capacity'] ?? 0), 4) }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono text-xs">{{ number_format(($r['mu_capital'] ?? 0), 4) }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono text-xs">
                                    {{ number_format(($r['mu_collateral'] ?? 0), 4) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-xs">{{ number_format(($r['mu_condition'] ?? 0), 4) }}
                                </td>
                                <td class="px-3 py-3 text-center font-mono font-semibold text-indigo-700">
                                    {{ number_format($r['alpha'], 4) }}</td>
                                <td class="px-3 py-3 text-center font-mono font-semibold">{{ number_format($r['z'], 4) }}</td>
                                <td class="px-3 py-3 text-center font-mono text-slate-600">{{ number_format($r['alpha_z'], 4) }}</td>
                                <td class="px-3 py-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $r['kelayakan'] === 'layak' ? 'badge-layak' : 'badge-tidak-layak' }}">{{ $r['kelayakan'] === 'layak' ? 'Layak' : 'Tidak Layak' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-6 mb-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2"><i
                    class="fa-solid fa-calculator text-indigo-500"></i> Defuzzifikasi (Weighted Average)</h3>
            @php $sumAZ = array_sum(array_column($analisis->detail_rule, 'alpha_z'));
            $sumA = array_sum(array_column($analisis->detail_rule, 'alpha')); @endphp
            <div class="bg-slate-50 rounded-xl p-5 font-mono text-sm">
                <div class="mb-2">z* = {{ number_format($sumAZ, 4) }} / {{ number_format($sumA, 4) }}</div>
                <div class="font-bold text-lg text-indigo-700">z* = {{ number_format($analisis->nilai_defuzzifikasi, 4) }} <span
                        class="text-slate-500 text-base font-normal ml-2">≈
                        {{ number_format($analisis->persentase_kelayakan, 2) }}%</span></div>
            </div>
            <div class="mt-4">
                <div class="relative h-4 bg-gradient-to-r from-red-300 via-yellow-300 to-green-400 rounded-full">
                    <div class="absolute top-1/2 -translate-y-1/2 w-0.5 h-6 bg-slate-700" style="left:70%"></div>
                    <div class="absolute top-1/2 -translate-y-1/2 w-5 h-5 rounded-full border-2 border-white shadow {{ $analisis->keputusan === 'Layak' ? 'bg-green-600' : 'bg-red-500' }}"
                        style="left:calc({{ min(max($analisis->persentase_kelayakan, 0), 100) }}% - 10px)"></div>
                </div>
                <p class="text-center text-sm text-slate-600 mt-3">Skor <strong
                        class="font-mono">{{ number_format($analisis->persentase_kelayakan, 2) }}</strong> → <strong
                        class="{{ $analisis->keputusan === 'Layak' ? 'text-green-600' : 'text-red-600' }}">{{ $analisis->keputusan }}</strong>
                </p>
            </div>
        </div>
    @endif
@endsection