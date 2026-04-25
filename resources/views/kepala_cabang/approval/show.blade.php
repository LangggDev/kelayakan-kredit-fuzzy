@extends('layouts.app')
@section('title', 'Review Analisis')
@section('page-title', 'Review & Approval Analisis')
@section('page-subtitle', $approval->calonNasabah->nama . ' — ' . $approval->created_at->format('d M Y'))

@section('content')

{{-- Result Banner --}}
<div class="rounded-2xl overflow-hidden p-6 text-white {{ $approval->keputusan === 'Layak' ? '' : '' }}"
    style="background:linear-gradient(135deg, #1a2e5a 0%, #2d4190 100%)">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                style="background:rgba(245,166,35,0.2)">
                <i class="fa-solid {{ $approval->keputusan === 'Layak' ? 'fa-circle-check text-green-400' : 'fa-circle-xmark text-red-400' }} text-2xl"></i>
            </div>
            <div>
                <p class="text-white/60 text-xs">Hasil Analisis Fuzzy Tsukamoto 5C</p>
                <h2 class="text-2xl font-bold text-white">{{ $approval->keputusan }}</h2>
                <p class="text-white/70 text-sm mt-0.5">
                    Skor: <span class="font-mono font-bold text-white">{{ number_format($approval->nilai_defuzzifikasi, 2) }}</span>
                    &mdash; <span style="color:#f5a623">{{ number_format($approval->persentase_kelayakan, 2) }}%</span>
                </p>
            </div>
        </div>
        <div class="flex-shrink-0">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border {{ $approval->status_color }}">
                <i class="fa-solid {{ $approval->status_icon }}"></i>
                {{ $approval->status_label }}
            </span>
        </div>
    </div>
</div>

{{-- 5C Scores --}}
<div class="grid grid-cols-5 gap-3">
    @php
    $cData = [
        ['C1','Character', $approval->skor_character,  '#2563eb','#dbeafe'],
        ['C2','Capacity',  $approval->skor_capacity,   '#16a34a','#dcfce7'],
        ['C3','Capital',   $approval->skor_capital,    '#d97706','#fef3c7'],
        ['C4','Collateral',$approval->skor_collateral, '#9333ea','#f3e8ff'],
        ['C5','Condition', $approval->skor_condition,  '#e11d48','#ffe4e6'],
    ];
    @endphp
    @foreach($cData as [$code, $name, $skor, $color, $bg])
    @php $skor = $skor ?? 0; @endphp
    <div class="card p-3 text-center">
        <div class="text-xs font-bold mb-0.5" style="color:{{ $color }}">{{ $code }}</div>
        <div class="text-xs text-slate-400 mb-1.5">{{ $name }}</div>
        <div class="text-xl font-bold" style="color:{{ $color }}">{{ number_format($skor, 0) }}</div>
        <div class="text-xs text-slate-400">/100</div>
        <div class="h-1.5 rounded-full mt-2 overflow-hidden" style="background:#e2e8f0">
            <div class="h-full rounded-full" style="width:{{ min($skor,100) }}%; background:{{ $color }}"></div>
        </div>
    </div>
    @endforeach
</div>

{{-- Info Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    {{-- Data Nasabah --}}
    <div class="card p-5">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm">
            <i class="fa-solid fa-user" style="color:#1a2e5a"></i> Data Calon Nasabah
        </h3>
        <dl class="space-y-2">
            @foreach(['Nama' => $approval->calonNasabah->nama, 'NIK' => $approval->calonNasabah->nik, 'Pekerjaan' => $approval->calonNasabah->pekerjaan ?? '-', 'Telepon' => $approval->calonNasabah->telepon ?? '-', 'Alamat' => $approval->calonNasabah->alamat ?? '-'] as $lbl => $val)
            <div class="flex justify-between py-1.5 border-b border-slate-50 last:border-0">
                <dt class="text-xs text-slate-500">{{ $lbl }}</dt>
                <dd class="text-xs font-semibold text-slate-800 text-right max-w-xs">{{ $val }}</dd>
            </div>
            @endforeach
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500">Kredit Analis</dt>
                <dd class="text-xs font-semibold text-slate-800">{{ $approval->user->name }}</dd>
            </div>
            <div class="flex justify-between py-1.5">
                <dt class="text-xs text-slate-500">Tanggal Analisis</dt>
                <dd class="text-xs font-semibold text-slate-800">{{ $approval->created_at->format('d M Y, H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Parameter 5C --}}
    <div class="card p-5">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm">
            <i class="fa-solid fa-sliders" style="color:#1a2e5a"></i> Parameter Input 5C
        </h3>
        <dl class="space-y-2">
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500 flex items-center gap-1.5"><span class="w-4 h-4 rounded text-white text-xs font-bold flex items-center justify-center" style="background:#2563eb;font-size:9px">C1</span>Skor Kredit</dt>
                <dd class="text-xs font-semibold text-slate-800">{{ number_format($approval->skor_kredit, 0) }} / 100</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500 flex items-center gap-1.5"><span class="w-4 h-4 rounded text-white text-xs font-bold flex items-center justify-center" style="background:#16a34a;font-size:9px">C2</span>Penghasilan/Bln</dt>
                <dd class="text-xs font-semibold text-slate-800">Rp {{ number_format($approval->penghasilan, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500 pl-5">Jumlah Pinjaman</dt>
                <dd class="text-xs font-semibold">Rp {{ number_format($approval->jumlah_pinjaman, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500 pl-5">Jangka Waktu</dt>
                <dd class="text-xs font-semibold">{{ $approval->jangka_waktu }} bulan</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500 pl-5">Rasio Cicilan (DSCR)</dt>
                <dd class="text-xs font-semibold {{ $approval->rasio_cicilan <= 30 ? 'text-green-600' : ($approval->rasio_cicilan <= 50 ? 'text-amber-600' : 'text-red-600') }}">
                    {{ number_format($approval->rasio_cicilan, 2) }}%
                </dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500 flex items-center gap-1.5"><span class="w-4 h-4 rounded text-white text-xs font-bold flex items-center justify-center" style="background:#d97706;font-size:9px">C3</span>Aset Bersih</dt>
                <dd class="text-xs font-semibold">Rp {{ number_format($approval->aset_bersih, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-50">
                <dt class="text-xs text-slate-500 flex items-center gap-1.5"><span class="w-4 h-4 rounded text-white text-xs font-bold flex items-center justify-center" style="background:#9333ea;font-size:9px">C4</span>Nilai Agunan</dt>
                <dd class="text-xs font-semibold">Rp {{ number_format($approval->nilai_agunan, 0, ',', '.') }}</dd>
            </div>
            <div class="flex justify-between py-1.5">
                <dt class="text-xs text-slate-500 flex items-center gap-1.5"><span class="w-4 h-4 rounded text-white text-xs font-bold flex items-center justify-center" style="background:#e11d48;font-size:9px">C5</span>Kondisi Ekonomi</dt>
                <dd class="text-xs font-semibold">{{ number_format($approval->kondisi_ekonomi, 0) }} / 100</dd>
            </div>
        </dl>
    </div>
</div>

{{-- Catatan Analis --}}
@if($approval->catatan)
<div class="card p-5">
    <h3 class="font-bold text-slate-800 mb-2 text-sm flex items-center gap-2">
        <i class="fa-solid fa-note-sticky" style="color:#1a2e5a"></i> Catatan Kredit Analis
    </h3>
    <p class="text-sm text-slate-600 bg-slate-50 rounded-xl p-3">{{ $approval->catatan }}</p>
</div>
@endif

{{-- APPROVAL SECTION --}}
@if($approval->status_approval === 'menunggu')
{{-- Approve / Reject Forms --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    {{-- Setujui --}}
    <div class="card p-5 border-2 border-green-200">
        <h3 class="font-bold text-green-700 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-600"></i> Setujui Analisis
        </h3>
        <p class="text-xs text-slate-500 mb-4">Analisis akan ditandai <strong>Disetujui</strong> dan dapat dilihat oleh Marketing.</p>
        <form action="{{ route('kepala_cabang.approval.approve', $approval) }}" method="POST">
            @csrf
            <textarea name="catatan_approval" rows="3" placeholder="Catatan persetujuan (opsional)..."
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 resize-none mb-3"></textarea>
            <button type="submit"
                onclick="return confirm('Yakin ingin MENYETUJUI analisis ini?')"
                class="w-full py-2.5 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2 transition-all hover:shadow-lg"
                style="background:linear-gradient(135deg,#16a34a,#15803d)">
                <i class="fa-solid fa-circle-check"></i> Setujui Analisis
            </button>
        </form>
    </div>

    {{-- Tolak --}}
    <div class="card p-5 border-2 border-red-200">
        <h3 class="font-bold text-red-700 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark text-red-600"></i> Tolak Analisis
        </h3>
        <p class="text-xs text-slate-500 mb-4">Analisis akan dikembalikan dengan status <strong>Ditolak</strong>. Wajib isi alasan penolakan.</p>
        <form action="{{ route('kepala_cabang.approval.reject', $approval) }}" method="POST">
            @csrf
            @error('catatan_approval')
            <div class="text-xs text-red-600 mb-2"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $message }}</div>
            @enderror
            <textarea name="catatan_approval" rows="3" placeholder="Alasan penolakan (wajib diisi)..."
                class="w-full px-3.5 py-2.5 border border-red-200 rounded-xl text-sm bg-red-50 resize-none mb-3 focus:border-red-400" required></textarea>
            <button type="submit"
                onclick="return confirm('Yakin ingin MENOLAK analisis ini?')"
                class="w-full py-2.5 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2 transition-all hover:shadow-lg"
                style="background:linear-gradient(135deg,#dc2626,#b91c1c)">
                <i class="fa-solid fa-circle-xmark"></i> Tolak Analisis
            </button>
        </form>
    </div>
</div>

@else
{{-- Already decided --}}
<div class="card p-5 border-2 {{ $approval->status_approval === 'disetujui' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full {{ $approval->status_approval === 'disetujui' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0">
                <i class="fa-solid {{ $approval->status_icon }} {{ $approval->status_approval === 'disetujui' ? 'text-green-600' : 'text-red-600' }}"></i>
            </div>
            <div>
                <p class="font-bold {{ $approval->status_approval === 'disetujui' ? 'text-green-800' : 'text-red-800' }}">
                    Analisis telah {{ $approval->status_label }}
                </p>
                <p class="text-xs text-slate-500 mt-0.5">
                    Oleh: {{ $approval->approvedBy?->name ?? '-' }}
                    &bull; {{ $approval->approved_at?->format('d M Y, H:i') }}
                </p>
                @if($approval->catatan_approval)
                <p class="text-xs text-slate-600 mt-2 bg-white/70 rounded-lg p-2">
                    "{{ $approval->catatan_approval }}"
                </p>
                @endif
            </div>
        </div>
        <form action="{{ route('kepala_cabang.approval.reset', $approval) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" onclick="return confirm('Reset status approval ke Menunggu?')"
                class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs text-slate-600 hover:bg-white transition-colors">
                <i class="fa-solid fa-rotate-left mr-1"></i> Reset
            </button>
        </form>
    </div>
</div>
@endif

{{-- Back button --}}
<div>
    <a href="{{ route('kepala_cabang.approval.index') }}"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

@endsection
