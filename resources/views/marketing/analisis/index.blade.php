@extends('layouts.app')

@section('title', 'Data Analisis Disetujui')
@section('page-title', 'Hasil Analisis Disetujui')
@section('page-subtitle', 'Daftar hasil analisis yang sudah disetujui Kepala Cabang')

@section('content')
<div class="space-y-5">
    <div class="card p-4">
        <form method="GET" action="{{ route('marketing.analisis.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-semibold text-slate-600 mb-1">Cari Nasabah / NIK</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Masukkan nama atau NIK..."
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300">
            </div>
            <div>
                <label for="keputusan" class="block text-xs font-semibold text-slate-600 mb-1">Keputusan</label>
                <select
                    id="keputusan"
                    name="keputusan"
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300">
                    <option value="">Semua Keputusan</option>
                    <option value="Layak" {{ request('keputusan') === 'Layak' ? 'selected' : '' }}>Layak</option>
                    <option value="Tidak Layak" {{ request('keputusan') === 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
                </select>
            </div>
            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold"
                    style="background:linear-gradient(135deg,#1a2e5a,#2d4190)">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                <a href="{{ route('marketing.analisis.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                    <i class="fa-solid fa-rotate-right"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Daftar Analisis</h3>
            <span class="text-xs text-slate-500">Total: {{ $dataAnalisis->total() }}</span>
        </div>

        @if($dataAnalisis->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Tanggal</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Nasabah</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">NIK</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Keputusan</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Skor</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Disetujui Oleh</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataAnalisis as $analisis)
                            <tr class="border-t border-slate-100 hover:bg-slate-50/60">
                                <td class="px-4 py-3 text-slate-600">{{ $analisis->created_at?->format('d M Y') }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $analisis->calonNasabah->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $analisis->calonNasabah->nik ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $analisis->keputusan === 'Layak' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $analisis->keputusan }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-700 font-semibold">
                                    {{ number_format($analisis->persentase_kelayakan, 2) }}%
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $analisis->approvedBy?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('marketing.analisis.show', $analisis) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-300 text-slate-700 hover:bg-white">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-slate-100">
                {{ $dataAnalisis->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-inbox text-lg"></i>
                </div>
                <p class="text-sm font-semibold text-slate-700">Belum ada data analisis</p>
                <p class="text-xs text-slate-500 mt-1">Tidak ditemukan data sesuai filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection
