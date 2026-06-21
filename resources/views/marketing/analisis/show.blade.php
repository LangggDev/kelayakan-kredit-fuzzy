@extends('layouts.app')
@section('title', 'Detail Analisis')
@section('page-title', 'Detail Hasil Analisis')
@section('page-subtitle', $analisis->calonNasabah->nama)

@section('content')

    {{-- Banner --}}
    <div class="rounded-2xl p-5 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
        style="background:linear-gradient(135deg,#1a2e5a,#2d4190)">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:rgba(245,166,35,0.2)">
                <i
                    class="fa-solid {{ $analisis->keputusan === 'Layak' ? 'fa-circle-check text-green-400' : 'fa-circle-xmark text-red-400' }} text-xl"></i>
            </div>
            <div>
                <p class="text-white/60 text-xs">Keputusan Fuzzy Tsukamoto 5C</p>
                <h2 class="text-2xl font-bold text-white">{{ $analisis->keputusan }}</h2>
                <p class="text-xs mt-0.5" style="color:#f5a623">
                    Skor: <strong class="font-mono">{{ number_format($analisis->persentase_kelayakan, 2) }}%</strong>
                    &mdash; Disetujui KC: {{ $analisis->approved_at?->format('d M Y') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold badge-disetujui border">
                <i class="fa-solid fa-stamp"></i> Disetujui Kepala Cabang
            </span>
            <a href="{{ route('marketing.analisis.index') }}"
                class="px-4 py-1.5 rounded-xl text-xs font-semibold text-white/80 hover:text-white border border-white/20 hover:bg-white/10 transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Read only notice --}}
    <div class="flex items-center gap-2 px-4 py-3 rounded-xl text-xs"
        style="background:#eef1f8; color:#1a2e5a; border:1px solid #d5ddef">
        <i class="fa-solid fa-lock flex-shrink-0"></i>
        <span>Mode <strong>Hanya Lihat</strong> — Anda tidak dapat mengubah data ini.</span>
    </div>

    {{-- 5C Scores --}}
    <div class="grid grid-cols-5 gap-3">
        @php
            $cData = [
                ['C1', 'Character', $analisis->skor_character, '#2563eb'],
                ['C2', 'Capacity', $analisis->skor_capacity, '#16a34a'],
                ['C3', 'Capital', $analisis->skor_capital, '#d97706'],
                ['C4', 'Collateral', $analisis->skor_collateral, '#9333ea'],
                ['C5', 'Condition', $analisis->skor_condition, '#e11d48'],
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
                    <div class="h-full rounded-full" style="width:{{ min($skor, 100) }}%; background:{{ $color }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 mb-3 text-sm flex items-center gap-2">
                <i class="fa-solid fa-user" style="color:#1a2e5a"></i> Data Calon Debitur
            </h3>
            <dl class="space-y-2">
                @foreach(['Nama' => $analisis->calonNasabah->nama, 'NIK' => $analisis->calonNasabah->nik, 'Pekerjaan' => $analisis->calonNasabah->pekerjaan ?? '-', 'Telepon' => $analisis->calonNasabah->telepon ?? '-', 'Alamat' => $analisis->calonNasabah->alamat ?? '-'] as $lbl => $val)
                    <div class="flex justify-between py-1.5 border-b border-slate-50 last:border-0">
                        <dt class="text-xs text-slate-500">{{ $lbl }}</dt>
                        <dd class="text-xs font-semibold text-slate-800 text-right">{{ $val }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 mb-3 text-sm flex items-center gap-2">
                <i class="fa-solid fa-sliders" style="color:#1a2e5a"></i> Ringkasan Kredit
            </h3>
            <dl class="space-y-2">
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <dt class="text-xs text-slate-500">Penghasilan/Bulan</dt>
                    <dd class="text-xs font-semibold">Rp {{ number_format($analisis->penghasilan, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <dt class="text-xs text-slate-500">Jumlah Pinjaman</dt>
                    <dd class="text-xs font-semibold">Rp {{ number_format($analisis->jumlah_pinjaman, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <dt class="text-xs text-slate-500">Jangka Waktu</dt>
                    <dd class="text-xs font-semibold">{{ $analisis->jangka_waktu }} bulan</dd>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <dt class="text-xs text-slate-500">Rasio Cicilan</dt>
                    <dd
                        class="text-xs font-semibold {{ $analisis->rasio_cicilan <= 30 ? 'text-green-600' : ($analisis->rasio_cicilan <= 50 ? 'text-amber-600' : 'text-red-600') }}">
                        {{ number_format($analisis->rasio_cicilan, 2) }}%</dd>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <dt class="text-xs text-slate-500">Nilai Agunan</dt>
                    <dd class="text-xs font-semibold">Rp {{ number_format($analisis->nilai_agunan, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <dt class="text-xs text-slate-500">Kredit Analis</dt>
                    <dd class="text-xs font-semibold">{{ $analisis->user->name }}</dd>
                </div>
                <div class="flex justify-between py-1.5">
                    <dt class="text-xs text-slate-500">Disetujui KC</dt>
                    <dd class="text-xs font-semibold text-green-600">{{ $analisis->approvedBy?->name }} &bull;
                        {{ $analisis->approved_at?->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Catatan KC --}}
    @if($analisis->catatan_approval)
        <div class="card p-4" style="background:#f0fdf4; border-color:#bbf7d0; border-width:1px">
            <p class="text-xs font-semibold text-green-700 mb-1 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-check"></i> Catatan Kepala Cabang
            </p>
            <p class="text-sm text-green-800">"{{ $analisis->catatan_approval }}"</p>
        </div>
    @endif

@endsection