@extends('layouts.app')
@section('title', 'Dashboard Marketing')
@section('page-title', 'Dashboard Marketing')
@section('page-subtitle', 'Lihat hasil analisis kelayakan kredit yang telah disetujui')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Analisis</p>
                    <p class="text-3xl font-bold mt-1" style="color:#1a2e5a">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#eef1f8">
                    <i class="fa-solid fa-file-waveform" style="color:#1a2e5a"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Disetujui KC</p>
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
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hasil Layak</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['layak'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <i class="fa-solid fa-thumbs-up text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="stat-card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tidak Layak</p>
                    <p class="text-3xl font-bold text-red-500 mt-1">{{ $stats['tidak_layak'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <i class="fa-solid fa-thumbs-down text-red-500"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Info box --}}
    <div class="rounded-xl p-4 flex items-center gap-3 border" style="background:#fef9ee; border-color:#fde68a">
        <i class="fa-solid fa-circle-info text-amber-500 flex-shrink-0"></i>
        <div class="text-sm text-amber-800">
            Anda hanya dapat melihat hasil analisis yang telah <strong>disetujui oleh Kepala Cabang</strong>.
            Untuk keperluan penawaran, pastikan menggunakan data yang sudah mendapat persetujuan.
        </div>
    </div>

    {{-- Recent Approved --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600"></i> Analisis Disetujui Terbaru
            </h3>
            <a href="{{ route('marketing.analisis.index') }}" class="text-xs font-semibold hover:underline"
                style="color:#1a2e5a">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100" style="background:#f4f6fb">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Debitur</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Analis</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Skor</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Keputusan</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase">Disetujui</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recent as $item)
                        <tr class="table-row">
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $item->calonNasabah->nama }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $item->calonNasabah->nik }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 text-xs">{{ $item->user->name }}</td>
                            <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-800">
                                {{ number_format($item->persentase_kelayakan, 1) }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->keputusan === 'Layak' ? 'badge-layak' : 'badge-tidak-layak' }}">
                                    {{ $item->keputusan }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-xs text-slate-400">
                                {{ $item->approved_at?->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('marketing.analisis.show', $item->id) }}"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white" style="background:#1a2e5a">
                                    <i class="fa-solid fa-eye mr-1"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                                Belum ada analisis yang disetujui
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection