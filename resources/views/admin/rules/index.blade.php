@extends('layouts.app')
@section('title', 'Rule Fuzzy')
@section('page-title', 'Manajemen Rule Fuzzy')
@section('page-subtitle', 'Kelola aturan IF-THEN untuk inferensi Fuzzy Tsukamoto')

@section('content')
<div class="flex flex-col sm:flex-row gap-3 mb-2">
    <form action="{{ route('admin.rules.index') }}" method="GET" class="flex gap-3 flex-1">
        <div class="relative flex-1">
            <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi rule..."
                class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
        </div>
        <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-white font-semibold text-sm">
            <i class="fa-solid fa-filter"></i>
        </button>
    </form>
    <a href="{{ route('admin.rules.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Rule
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">R#</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Character</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Capacity</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Capital</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Collateral</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Condition</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Hasil</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Output Tipe</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="px-4 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($rules as $rule)
                @php
                $himpunanColor = [
                    'baik' => 'bg-blue-50 text-blue-700',
                    'cukup' => 'bg-yellow-50 text-yellow-700',
                    'buruk' => 'bg-red-50 text-red-700',
                    'sangat layak' => 'bg-green-50 text-green-700',
                    'layak' => 'bg-blue-50 text-blue-700',
                    'tidak layak' => 'bg-red-50 text-red-700',
                    'any' => 'bg-slate-100 text-slate-500',
                ];
                @endphp
                <tr class="table-row">
                    <td class="px-4 py-3.5 text-center">
                        <span class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center mx-auto">{{ $rule->nomor_rule }}</span>
                    </td>
                    @foreach([$rule->character, $rule->capacity, $rule->capital, $rule->collateral, $rule->condition] as $val)
                    <td class="px-4 py-3.5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize {{ $himpunanColor[$val] ?? 'bg-slate-100 text-slate-600' }}">{{ $val }}</span>
                    </td>
                    @endforeach
                    <td class="px-4 py-3.5 text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $rule->kelayakan === 'layak' ? 'badge-layak' : 'badge-tidak-layak' }}">
                            {{ $rule->kelayakan === 'layak' ? 'LAYAK' : 'TDK LAYAK' }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-center text-xs text-slate-500 capitalize">{{ str_replace('_',' ',$rule->output_tipe) }}</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="w-2 h-2 rounded-full inline-block {{ $rule->is_active ? 'bg-green-500' : 'bg-slate-300' }}"></span>
                    </td>
                    <td class="px-4 py-3.5 text-right">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('admin.rules.edit', $rule->id) }}" class="p-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.rules.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('Hapus rule #' + {{ $rule->nomor_rule }} + '?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-14 text-center text-slate-400"><i class="fa-solid fa-code-branch text-2xl mb-2 block"></i>Belum ada rule fuzzy</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rules->hasPages())
    <div class="px-6 py-4 border-t border-slate-50">{{ $rules->links() }}</div>
    @endif
</div>

<!-- Legend -->
<div class="card p-4">
    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Keterangan Warna Himpunan</p>
    <div class="flex flex-wrap gap-2">
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">sangat layak</span>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">baik / layak</span>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">cukup</span>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">buruk / tidak layak</span>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">any (semua)</span>
    </div>
</div>
@endsection
