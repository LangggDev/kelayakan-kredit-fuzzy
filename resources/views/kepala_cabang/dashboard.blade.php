@extends('layouts.app')
@section('title', 'Dashboard Kepala Cabang')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu Approval</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['menunggu'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">Perlu ditindaklanjuti</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-amber-500"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['disetujui'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tidak Layak</p>
                    <p class="text-3xl font-bold text-slate-500 mt-1">{{ $stats['tidak_layak'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-ban text-slate-400"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Analisis</p>
                    <p class="text-3xl font-bold text-navy-700 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#eef1f8">
                    <i class="fa-solid fa-file-waveform" style="color:#1a2e5a"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Menunggu Approval --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between"
                style="background:linear-gradient(135deg,#fef9ee,#fdf0ce)">
                <div>
                    <h3 class="font-bold text-amber-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-clock text-amber-500"></i> Menunggu Approval
                    </h3>
                    <p class="text-xs text-amber-600 mt-0.5">Analisis layak yang perlu approval</p>
                </div>
                @if($stats['menunggu'] > 0)
                    <span
                        class="bg-amber-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $stats['menunggu'] }}</span>
                @endif
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($menungguList as $item)
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0 text-white"
                            style="background:#1a2e5a">
                            {{ strtoupper(substr($item->calonNasabah->nama, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-slate-800 text-sm truncate">{{ $item->calonNasabah->nama }}</div>
                            <div class="text-xs text-slate-400">Analis: {{ $item->user->name }} &bull;
                                {{ $item->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span
                                class="text-xs font-semibold {{ $item->keputusan === 'Layak' ? 'badge-layak' : 'badge-tidak-layak' }} px-2 py-0.5 rounded-full">
                                {{ $item->keputusan }}
                            </span>
                            <a href="{{ route('kepala_cabang.approval.show', $item->id) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white btn-gold">
                                Tanda Tangan
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-slate-400 text-sm">
                        <i class="fa-solid fa-check-circle text-2xl text-green-400 mb-2 block"></i>
                        Tidak ada analisis yang menunggu persetujuan
                    </div>
                @endforelse
            </div>
            @if($stats['menunggu'] > 5)
                <div class="px-5 py-3 border-t border-slate-100 text-center">
                    <a href="{{ route('kepala_cabang.approval.index', ['status' => 'menunggu']) }}"
                        class="text-xs font-semibold hover:underline" style="color:#1a2e5a">
                        Lihat semua {{ $stats['menunggu'] }} yang menunggu →
                    </a>
                </div>
            @endif
        </div>

        {{-- Recent Decisions --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-stamp" style="color:#1a2e5a"></i> Keputusan Terbaru Anda
                </h3>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentApproved as $item)
                    <div class="flex items-center gap-3 px-5 py-3.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-green-100">
                            <i class="fa-solid fa-circle-check text-sm text-green-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-slate-800 text-sm truncate">{{ $item->calonNasabah->nama }}</div>
                            <div class="text-xs text-slate-400">{{ $item->approved_at?->diffForHumans() }}</div>
                        </div>
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $item->status_color }}">
                            {{ $item->status_label }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-slate-400 text-sm">
                        <i class="fa-solid fa-inbox text-2xl mb-2 block"></i> Belum ada keputusan
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick action --}}
    <div class="card p-5 flex items-center justify-between gap-4"
        style="background:linear-gradient(135deg,#1a2e5a,#2d4190)">
        <div>
            <p class="font-bold text-white text-sm">Mulai Review Analisis</p>
            <p class="text-xs mt-0.5" style="color:rgba(245,166,35,0.8)">Terdapat {{ $stats['menunggu'] }} analisis yang
                menunggu tanda tangan Anda</p>
        </div>
        <a href="{{ route('kepala_cabang.approval.index') }}"
            class="btn-gold flex items-center gap-2 px-5 py-2.5 rounded-xl text-navy-800 font-bold text-sm flex-shrink-0">
            <i class="fa-solid fa-stamp"></i> Buka Approval
        </a>
    </div>

@endsection