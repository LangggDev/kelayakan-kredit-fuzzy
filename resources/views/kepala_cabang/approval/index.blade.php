@extends('layouts.app')
@section('title', 'Approval Analisis')
@section('page-title', 'Approval Analisis Kredit')
@section('page-subtitle', 'Review dan setujui hasil analisis dari Kredit Analis')

@section('content')

{{-- Filter --}}
<div class="card p-4">
    <form action="{{ route('kepala_cabang.approval.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 flex-wrap">
        <div class="relative flex-1 min-w-48">
            <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK nasabah..."
                class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
        </div>
        <select name="status" class="px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            <option value="">Semua Status</option>
            <option value="menunggu"  {{ request('status')==='menunggu'  ?'selected':'' }}>⏳ Menunggu</option>
            <option value="disetujui" {{ request('status')==='disetujui' ?'selected':'' }}>✅ Disetujui</option>
            <option value="ditolak"   {{ request('status')==='ditolak'   ?'selected':'' }}>❌ Ditolak</option>
        </select>
        <select name="keputusan" class="px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            <option value="">Semua Keputusan Fuzzy</option>
            <option value="Layak"       {{ request('keputusan')==='Layak'      ?'selected':'' }}>Layak</option>
            <option value="Tidak Layak" {{ request('keputusan')==='Tidak Layak'?'selected':'' }}>Tidak Layak</option>
        </select>
        <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request()->anyFilled(['search','status','keputusan']))
        <a href="{{ route('kepala_cabang.approval.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50 flex items-center gap-2">
            <i class="fa-solid fa-xmark"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100" style="background:#f4f6fb">
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Calon Nasabah</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kredit Analis</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Skor</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keputusan Fuzzy</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Approval</th>
                    <th class="text-right px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($approvals as $item)
                <tr class="table-row {{ $item->status_approval === 'menunggu' ? 'bg-amber-50/30' : '' }}">
                    <td class="px-5 py-4 text-slate-400 text-xs">{{ $approvals->firstItem() + $loop->index }}</td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-800">{{ $item->calonNasabah->nama }}</div>
                        <div class="text-xs text-slate-400 font-mono">{{ $item->calonNasabah->nik }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-slate-700 text-sm">{{ $item->user->name }}</div>
                        <div class="text-xs text-slate-400">Kredit Analis</div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="font-mono font-bold text-slate-800">{{ number_format($item->persentase_kelayakan, 1) }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->keputusan === 'Layak' ? 'badge-layak' : 'badge-tidak-layak' }}">
                            <i class="fa-solid {{ $item->keputusan === 'Layak' ? 'fa-check' : 'fa-xmark' }}"></i>
                            {{ $item->keputusan }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $item->status_color }}">
                            <i class="fa-solid {{ $item->status_icon }} text-xs"></i>
                            {{ $item->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-right text-xs text-slate-400">
                        {{ $item->created_at->format('d M Y') }}<br>
                        <span class="text-slate-300">{{ $item->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('kepala_cabang.approval.show', $item->id) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white btn-gold">
                            <i class="fa-solid fa-stamp"></i>
                            {{ $item->status_approval === 'menunggu' ? 'Review' : 'Detail' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-14 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-inbox text-slate-400 text-xl"></i>
                        </div>
                        <p class="text-slate-500 font-medium">Tidak ada data analisis</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($approvals->hasPages())
    <div class="px-5 py-4 border-t border-slate-50">{{ $approvals->links() }}</div>
    @endif
</div>
@endsection
