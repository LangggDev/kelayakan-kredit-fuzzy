@extends('layouts.app')
@section('title', 'Semua Hasil Analisis')
@section('page-title', 'Semua Hasil Analisis')
@section('page-subtitle', 'Seluruh data analisis kelayakan kredit 5C')

@section('content')

{{-- Filter --}}
<div class="card p-4">
    <form action="{{ route('admin.analisis.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 flex-wrap">
        <div class="relative flex-1 min-w-48">
            <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK nasabah..."
                class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
        </div>
        <select name="keputusan" class="px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            <option value="">Semua Keputusan</option>
            <option value="Layak"       {{ request('keputusan')==='Layak'      ?'selected':'' }}>✅ Layak</option>
            <option value="Tidak Layak" {{ request('keputusan')==='Tidak Layak'?'selected':'' }}>❌ Tidak Layak</option>
        </select>
        <select name="analis" class="px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            <option value="">Semua Analis</option>
            @foreach($analisList as $a)
            <option value="{{ $a->id }}" {{ request('analis')==$a->id?'selected':'' }}>{{ $a->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        @if(request()->anyFilled(['search','keputusan','analis']))
        <a href="{{ route('admin.analisis.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50 flex items-center gap-2">
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
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase w-8">No</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Calon Nasabah</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Analis</th>
                    {{-- Skor 5C --}}
                    <th class="text-center px-2 py-3.5">
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">C1</span>
                    </th>
                    <th class="text-center px-2 py-3.5">
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">C2</span>
                    </th>
                    <th class="text-center px-2 py-3.5">
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">C3</span>
                    </th>
                    <th class="text-center px-2 py-3.5">
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">C4</span>
                    </th>
                    <th class="text-center px-2 py-3.5">
                        <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">C5</span>
                    </th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Skor</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Keputusan</th>
                    <th class="text-right px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($hasilAnalisis as $item)
                <tr class="table-row">
                    <td class="px-4 py-3.5 text-slate-400 text-xs">{{ $hasilAnalisis->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3.5">
                        <div class="font-medium text-slate-800">{{ $item->calonNasabah->nama }}</div>
                        <div class="text-xs text-slate-400 font-mono">{{ $item->calonNasabah->nik }}</div>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="text-slate-600 text-xs">{{ $item->user->name }}</div>
                    </td>
                    {{-- Skor 5C mini bars --}}
                    @foreach(['skor_character'=>'blue','skor_capacity'=>'green','skor_capital'=>'amber','skor_collateral'=>'purple','skor_condition'=>'rose'] as $col => $color)
                    @php $skor = $item->$col ?? 0; @endphp
                    <td class="px-2 py-3.5 text-center">
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="text-xs font-mono font-semibold text-slate-700">{{ number_format($skor,0) }}</span>
                            <div class="w-8 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-{{ $color }}-400" style="width:{{ min($skor,100) }}%"></div>
                            </div>
                        </div>
                    </td>
                    @endforeach
                    {{-- Skor total --}}
                    <td class="px-4 py-3.5 text-center">
                        <span class="font-mono font-bold text-slate-800 text-sm">{{ number_format($item->persentase_kelayakan, 1) }}</span>
                    </td>
                    {{-- Keputusan --}}
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->keputusan==='Layak' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            <i class="fa-solid {{ $item->keputusan==='Layak' ? 'fa-check' : 'fa-xmark' }}"></i>
                            {{ $item->keputusan }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-right text-xs text-slate-400">
                        {{ $item->created_at->format('d M Y') }}<br>
                        <span class="text-slate-300">{{ $item->created_at->format('H:i') }}</span>
                    </td>
                    {{-- Aksi --}}
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1.5 justify-end">
                            {{-- Detail --}}
                            <a href="{{ route('admin.analisis.show', $item->id) }}"
                                class="p-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors" title="Detail">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            {{-- Download PDF --}}
                            <a href="{{ route('admin.analisis.pdf', $item->id) }}"
                                class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Unduh PDF">
                                <i class="fa-solid fa-file-pdf text-xs"></i>
                            </a>
                            {{-- Hapus --}}
                            <form action="{{ route('admin.analisis.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus data analisis ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" class="px-6 py-14 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-inbox text-slate-400 text-2xl"></i>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada data analisis</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($hasilAnalisis->hasPages())
    <div class="px-6 py-4 border-t border-slate-50">{{ $hasilAnalisis->links() }}</div>
    @endif
</div>
@endsection
