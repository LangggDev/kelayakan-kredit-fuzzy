@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan sistem kelayakan kredit')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="stat-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Analisis</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_analisis'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Semua waktu</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="fa-solid fa-file-waveform text-indigo-600"></i>
            </div>
        </div>
    </div>
    <div class="stat-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Layak</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['total_layak'] }}</p>
                @if($stats['total_analisis'] > 0)
                <p class="text-xs text-slate-400 mt-1">{{ round($stats['total_layak']/$stats['total_analisis']*100) }}% dari total</p>
                @endif
            </div>
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-green-600"></i>
            </div>
        </div>
    </div>
    <div class="stat-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Tidak Layak</p>
                <p class="text-3xl font-bold text-red-500 mt-1">{{ $stats['total_tidak_layak'] }}</p>
                @if($stats['total_analisis'] > 0)
                <p class="text-xs text-slate-400 mt-1">{{ round($stats['total_tidak_layak']/$stats['total_analisis']*100) }}% dari total</p>
                @endif
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="fa-solid fa-circle-xmark text-red-500"></i>
            </div>
        </div>
    </div>
    <div class="stat-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Kredit Analis</p>
                <p class="text-3xl font-bold text-violet-600 mt-1">{{ $stats['total_analis'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Terdaftar</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                <i class="fa-solid fa-users text-violet-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart + Info -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Bar Chart -->
    <div class="lg:col-span-2 card p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Tren Analisis (6 Bulan Terakhir)</h3>
        <p class="text-xs text-slate-400 mb-5">Jumlah analisis layak vs tidak layak per bulan</p>
        <div class="flex items-end gap-2 h-40">
            @foreach($chartData as $data)
            @php
                $maxVal = max(array_map(fn($d) => $d['layak'] + $d['tidak_layak'], $chartData));
                $total = $data['layak'] + $data['tidak_layak'];
                $heightLayak = $maxVal > 0 ? round(($data['layak']/$maxVal)*100) : 0;
                $heightTidak = $maxVal > 0 ? round(($data['tidak_layak']/$maxVal)*100) : 0;
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full flex items-end gap-0.5" style="height:120px">
                    <div class="flex-1 rounded-t-md bg-indigo-500 transition-all" style="height:{{ $heightLayak }}%"></div>
                    <div class="flex-1 rounded-t-md bg-red-400 transition-all" style="height:{{ $heightTidak }}%"></div>
                </div>
                <span class="text-xs text-slate-400 text-center">{{ substr($data['label'],0,3) }}</span>
            </div>
            @endforeach
        </div>
        <div class="flex items-center gap-4 mt-3">
            <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="w-3 h-3 rounded bg-indigo-500"></span>Layak</span>
            <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="w-3 h-3 rounded bg-red-400"></span>Tidak Layak</span>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="card p-6 flex flex-col gap-4">
        <h3 class="font-semibold text-slate-800">Informasi Sistem</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                <span class="text-sm text-slate-500">Total Nasabah</span>
                <span class="font-semibold text-slate-800">{{ $stats['total_nasabah'] }}</span>
            </div>
            <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                <span class="text-sm text-slate-500">Rule Fuzzy Aktif</span>
                <span class="font-semibold text-slate-800">{{ $stats['total_rule'] }}</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <span class="text-sm text-slate-500">Metode</span>
                <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full">Tsukamoto</span>
            </div>
        </div>
        <div class="mt-auto">
            <a href="{{ route('admin.analisis.index') }}" class="btn-primary w-full py-2.5 rounded-xl text-white font-semibold text-sm text-center block">
                <i class="fa-solid fa-eye mr-2"></i>Lihat Semua Analisis
            </a>
        </div>
    </div>
</div>

<!-- Recent Analysis -->
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
        <h3 class="font-semibold text-slate-800">Analisis Terbaru</h3>
        <a href="{{ route('admin.analisis.index') }}" class="text-xs text-indigo-600 font-medium hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nasabah</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Analis</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Skor</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keputusan</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($recentAnalisis as $item)
                <tr class="table-row">
                    <td class="px-6 py-3.5">
                        <div class="font-medium text-slate-800">{{ $item->calonNasabah->nama }}</div>
                        <div class="text-xs text-slate-400">{{ $item->calonNasabah->nik }}</div>
                    </td>
                    <td class="px-6 py-3.5 text-slate-600">{{ $item->user->name }}</td>
                    <td class="px-6 py-3.5 text-right">
                        <span class="font-mono font-semibold text-slate-800">{{ number_format($item->persentase_kelayakan, 1) }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->keputusan === 'Layak' ? 'badge-layak' : 'badge-tidak-layak' }}">
                            <i class="fa-solid {{ $item->keputusan === 'Layak' ? 'fa-check' : 'fa-xmark' }}"></i>
                            {{ $item->keputusan }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-right text-slate-400 text-xs">{{ $item->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                        <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                        Belum ada data analisis
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
