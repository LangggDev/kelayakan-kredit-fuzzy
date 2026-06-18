@extends('layouts.app')
@section('title', 'Detail Analisis')
@section('page-title', 'Detail Hasil Analisis')
@section('page-subtitle', $analisis->calonNasabah->nama . ' — ' . $analisis->created_at->format('d M Y'))

@section('content')

{{-- ══ BANNER TIDAK LAYAK ══ --}}
@if($analisis->status_approval === 'tidak_layak')
<div class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50">
    <i class="fa-solid fa-ban text-slate-400 text-xl flex-shrink-0"></i>
    <div>
        <p class="font-bold text-slate-600 text-sm">Hasil Analisis: Tidak Layak</p>
        <p class="text-xs text-slate-400">Analisis ini tercatat otomatis sebagai Tidak Layak. Tidak memerlukan persetujuan Kepala Cabang.</p>
    </div>
</div>
@endif

{{-- ══ BANNER DISETUJUI ══ --}}
@if($analisis->status_approval === 'disetujui')
<div class="flex items-center gap-3 p-4 rounded-xl border-2 border-green-200 bg-green-50">
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

{{-- ══ BANNER MENUNGGU ══ --}}
@if($analisis->status_approval === 'menunggu')
<div class="flex items-center gap-3 p-4 rounded-xl border-2 border-amber-200 bg-amber-50">
    <i class="fa-solid fa-clock text-amber-500 text-xl flex-shrink-0"></i>
    <div>
        <p class="font-bold text-amber-800 text-sm">Menunggu Tanda Tangan Kepala Cabang</p>
        <p class="text-xs text-amber-600">Analisis ini sedang dalam antrian tanda tangan Kepala Cabang.</p>
    </div>
</div>
@endif

{{-- Result banner --}}
<div class="rounded-2xl p-5 text-white" style="background:linear-gradient(135deg,#1a2e5a,#2d4190)">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(245,166,35,0.2)">
            <i class="fa-solid {{ $analisis->keputusan === 'Layak' ? 'fa-circle-check text-green-400' : 'fa-circle-xmark text-red-400' }} text-2xl"></i>
        </div>
        <div>
            <p class="text-white/60 text-xs">Hasil Analisis Fuzzy Tsukamoto 5C</p>
            <h2 class="text-2xl font-bold">{{ $analisis->keputusan }}</h2>
            <p class="text-xs mt-0.5" style="color:#f5a623">
                Skor: <strong class="font-mono">{{ number_format($analisis->nilai_defuzzifikasi, 2) }}</strong>
                &mdash; Kelayakan: <strong>{{ number_format($analisis->persentase_kelayakan, 2) }}%</strong>
            </p>
        </div>
    </div>
</div>

{{-- 5C Scores --}}
<div class="grid grid-cols-5 gap-3">
    @php
    $cData = [
        ['C1','Character', $analisis->skor_character,  '#2563eb'],
        ['C2','Capacity',  $analisis->skor_capacity,   '#16a34a'],
        ['C3','Capital',   $analisis->skor_capital,    '#d97706'],
        ['C4','Collateral',$analisis->skor_collateral, '#9333ea'],
        ['C5','Condition', $analisis->skor_condition,  '#e11d48'],
    ];
    @endphp
    @foreach($cData as [$code, $name, $skor, $color])
    @php $skor = $skor ?? 0; @endphp
    <div class="card p-3 text-center">
        <div class="text-xs font-bold mb-0.5" style="color:{{ $color }}">{{ $code }}</div>
        <div class="text-xs text-slate-400 mb-1">{{ $name }}</div>
        <div class="text-xl font-bold" style="color:{{ $color }}">{{ number_format($skor, 0) }}</div>
        <div class="text-xs text-slate-400">/100</div>
        <div class="h-1.5 rounded-full mt-1.5 overflow-hidden" style="background:#e2e8f0">
            <div class="h-full rounded-full" style="width:{{ min($skor,100) }}%; background:{{ $color }}"></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Info Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="card p-5">
        <h3 class="font-bold text-slate-800 mb-3 text-sm flex items-center gap-2">
            <i class="fa-solid fa-user" style="color:#1a2e5a"></i> Data Calon Nasabah
        </h3>
        <dl class="space-y-2">
            @foreach(['Nama' => $analisis->calonNasabah->nama, 'NIK' => $analisis->calonNasabah->nik, 'Pekerjaan' => $analisis->calonNasabah->pekerjaan ?? '-', 'Telepon' => $analisis->calonNasabah->telepon ?? '-', 'Alamat' => $analisis->calonNasabah->alamat ?? '-'] as $lbl => $val)
            <div class="flex justify-between py-1.5 border-b border-slate-50 last:border-0">
                <dt class="text-xs text-slate-500">{{ $lbl }}</dt>
                <dd class="text-xs font-semibold text-slate-800 text-right max-w-xs">{{ $val }}</dd>
            </div>
            @endforeach
        </dl>
    </div>
    <div class="card p-5">
        <h3 class="font-bold text-slate-800 mb-3 text-sm flex items-center gap-2">
            <i class="fa-solid fa-sliders" style="color:#1a2e5a"></i> Parameter Input 5C
        </h3>
        <dl class="space-y-2">
            <div class="flex justify-between py-1.5 border-b border-slate-50"><dt class="text-xs text-slate-500 flex items-center gap-1"><span class="w-4 h-4 rounded text-white flex items-center justify-center font-bold" style="background:#2563eb;font-size:8px">C1</span>Skor Kredit</dt><dd class="text-xs font-semibold">{{ number_format($analisis->skor_kredit, 0) }} / 100</dd></div>
            <div class="flex justify-between py-1.5 border-b border-slate-50"><dt class="text-xs text-slate-500 flex items-center gap-1"><span class="w-4 h-4 rounded text-white flex items-center justify-center font-bold" style="background:#16a34a;font-size:8px">C2</span>Penghasilan/Bln</dt><dd class="text-xs font-semibold">Rp {{ number_format($analisis->penghasilan, 0, ',', '.') }}</dd></div>
            <div class="flex justify-between py-1.5 border-b border-slate-50"><dt class="text-xs text-slate-500 pl-5">Jumlah Pinjaman</dt><dd class="text-xs font-semibold">Rp {{ number_format($analisis->jumlah_pinjaman, 0, ',', '.') }}</dd></div>
            <div class="flex justify-between py-1.5 border-b border-slate-50"><dt class="text-xs text-slate-500 pl-5">Jangka Waktu</dt><dd class="text-xs font-semibold">{{ $analisis->jangka_waktu }} bulan</dd></div>
            <div class="flex justify-between py-1.5 border-b border-slate-50"><dt class="text-xs text-slate-500 pl-5">Rasio Cicilan</dt><dd class="text-xs font-semibold {{ $analisis->rasio_cicilan <= 30 ? 'text-green-600' : ($analisis->rasio_cicilan <= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ number_format($analisis->rasio_cicilan, 2) }}%</dd></div>
            <div class="flex justify-between py-1.5 border-b border-slate-50"><dt class="text-xs text-slate-500 flex items-center gap-1"><span class="w-4 h-4 rounded text-white flex items-center justify-center font-bold" style="background:#d97706;font-size:8px">C3</span>Aset Bersih</dt><dd class="text-xs font-semibold">Rp {{ number_format($analisis->aset_bersih, 0, ',', '.') }}</dd></div>
            <div class="flex justify-between py-1.5 border-b border-slate-50"><dt class="text-xs text-slate-500 flex items-center gap-1"><span class="w-4 h-4 rounded text-white flex items-center justify-center font-bold" style="background:#9333ea;font-size:8px">C4</span>Nilai Agunan</dt><dd class="text-xs font-semibold">Rp {{ number_format($analisis->nilai_agunan, 0, ',', '.') }}</dd></div>
            <div class="flex justify-between py-1.5"><dt class="text-xs text-slate-500 flex items-center gap-1"><span class="w-4 h-4 rounded text-white flex items-center justify-center font-bold" style="background:#e11d48;font-size:8px">C5</span>Kondisi Ekonomi</dt><dd class="text-xs font-semibold">{{ number_format($analisis->kondisi_ekonomi, 0) }} / 100</dd></div>
        </dl>
    </div>
</div>

{{-- Tombol aksi bawah --}}
<div class="flex flex-wrap items-center gap-3">
    <a href="{{ route('analis.analisis.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('analis.analisis.pdf', $analisis->id) }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">
        <i class="fa-solid fa-file-pdf text-red-500"></i> Export PDF
    </a>
</div>

@endsection
