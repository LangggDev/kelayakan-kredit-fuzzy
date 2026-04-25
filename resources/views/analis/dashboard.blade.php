@extends('layouts.app')
@section('title', 'Dashboard Analis')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="stat-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Analisis</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_analisis'] }}</p>
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
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="fa-solid fa-circle-xmark text-red-500"></i>
            </div>
        </div>
    </div>
    <div class="stat-card p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Nasabah</p>
                <p class="text-3xl font-bold text-violet-600 mt-1">{{ $stats['total_nasabah'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                <i class="fa-solid fa-users text-violet-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick action + Recent -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="card p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Aksi Cepat</h3>
        <a href="{{ route('analis.analisis.create') }}" class="btn-primary flex items-center gap-3 p-4 rounded-xl text-white group mb-3">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center group-hover:bg-white/30 flex-shrink-0">
                <i class="fa-solid fa-plus text-white"></i>
            </div>
            <div>
                <div class="font-semibold text-sm">Analisis Baru</div>
                <div class="text-xs text-white/70">Input data nasabah untuk dianalisis</div>
            </div>
        </a>
        <a href="{{ route('analis.analisis.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all group">
            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-indigo-100 flex-shrink-0">
                <i class="fa-solid fa-clock-rotate-left text-slate-500 group-hover:text-indigo-600"></i>
            </div>
            <div>
                <div class="font-semibold text-sm text-slate-700">Riwayat Analisis</div>
                <div class="text-xs text-slate-400">Lihat semua analisis yang pernah dilakukan</div>
            </div>
        </a>

        @if($stats['total_analisis'] > 0)
        <div class="mt-5 pt-4 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Tingkat Kelayakan</p>
            <div class="progress-bar mb-1.5">
                <div class="progress-fill" style="width:{{ $stats['total_analisis'] > 0 ? round($stats['total_layak']/$stats['total_analisis']*100) : 0 }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-slate-400">
                <span>{{ $stats['total_layak'] }} Layak</span>
                <span>{{ $stats['total_analisis'] > 0 ? round($stats['total_layak']/$stats['total_analisis']*100) : 0 }}%</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Recent -->
    <div class="lg:col-span-2 card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
            <h3 class="font-semibold text-slate-800">Analisis Terbaru Anda</h3>
            <a href="{{ route('analis.analisis.index') }}" class="text-xs text-indigo-600 font-medium hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nasabah</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Skor</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keputusan</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentAnalisis as $item)
                    <tr class="table-row cursor-pointer" onclick="window.location='{{ route('analis.analisis.show', $item->id) }}'">
                        <td class="px-6 py-3.5">
                            <div class="font-medium text-slate-800">{{ $item->calonNasabah->nama }}</div>
                            <div class="text-xs text-slate-400">{{ $item->calonNasabah->nik }}</div>
                        </td>
                        <td class="px-6 py-3.5 text-right font-mono font-semibold text-slate-800">
                            {{ number_format($item->persentase_kelayakan, 1) }}
                        </td>
                        <td class="px-6 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->keputusan === 'Layak' ? 'badge-layak' : 'badge-tidak-layak' }}">
                                {{ $item->keputusan }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right text-slate-400 text-xs">{{ $item->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                            <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                            Belum ada analisis. <a href="{{ route('analis.analisis.create') }}" class="text-indigo-600 hover:underline">Mulai analisis</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
