@extends('layouts.app')
@section('title', 'Parameter Fuzzy')
@section('page-title', 'Parameter Fuzzy')
@section('page-subtitle', 'Kelola fungsi keanggotaan untuk setiap kriteria penilaian kredit')

@section('content')

@php
$colorMap = [
    'blue'   => ['bg'=>'bg-blue-50',   'border'=>'border-blue-200',   'header'=>'from-blue-50 to-indigo-50',   'badge'=>'bg-blue-600',   'icon_bg'=>'bg-blue-100 text-blue-600',   'himpunan_bg'=>'bg-blue-100 text-blue-700',   'bar'=>'bg-blue-500',   'title'=>'text-blue-800'],
    'green'  => ['bg'=>'bg-green-50',  'border'=>'border-green-200',  'header'=>'from-green-50 to-emerald-50', 'badge'=>'bg-green-600',  'icon_bg'=>'bg-green-100 text-green-600', 'himpunan_bg'=>'bg-green-100 text-green-700', 'bar'=>'bg-green-500',  'title'=>'text-green-800'],
    'amber'  => ['bg'=>'bg-amber-50',  'border'=>'border-amber-200',  'header'=>'from-amber-50 to-yellow-50',  'badge'=>'bg-amber-500',  'icon_bg'=>'bg-amber-100 text-amber-600', 'himpunan_bg'=>'bg-amber-100 text-amber-700', 'bar'=>'bg-amber-500',  'title'=>'text-amber-800'],
    'purple' => ['bg'=>'bg-purple-50', 'border'=>'border-purple-200', 'header'=>'from-purple-50 to-violet-50', 'badge'=>'bg-purple-600', 'icon_bg'=>'bg-purple-100 text-purple-600','himpunan_bg'=>'bg-purple-100 text-purple-700','bar'=>'bg-purple-500', 'title'=>'text-purple-800'],
    'rose'   => ['bg'=>'bg-rose-50',   'border'=>'border-rose-200',   'header'=>'from-rose-50 to-pink-50',     'badge'=>'bg-rose-600',   'icon_bg'=>'bg-rose-100 text-rose-600',   'himpunan_bg'=>'bg-rose-100 text-rose-700',   'bar'=>'bg-rose-500',   'title'=>'text-rose-800'],
];

$tipeFungsiLabel = [
    'linear_naik'  => ['label' => 'Linear Naik',  'icon' => 'fa-arrow-trend-up',   'color' => 'text-green-600 bg-green-50'],
    'linear_turun' => ['label' => 'Linear Turun', 'icon' => 'fa-arrow-trend-down', 'color' => 'text-red-500 bg-red-50'],
    'segitiga'     => ['label' => 'Segitiga',      'icon' => 'fa-caret-up',         'color' => 'text-indigo-600 bg-indigo-50'],
];
@endphp

{{-- TOP ACTION BAR --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-2">
    <div class="flex items-center gap-2 flex-wrap">
        @foreach($meta as $key => $m)
        @php $c = $colorMap[$m['color']]; @endphp
        <a href="#{{ $key }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $c['himpunan_bg'] }} hover:opacity-80 transition-opacity">
            <i class="fa-solid {{ $m['icon'] }} text-xs"></i> {{ $m['kode'] }}
        </a>
        @endforeach
    </div>
    <a href="{{ route('admin.parameter.create') }}" class="btn-primary flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold text-sm flex-shrink-0">
        <i class="fa-solid fa-plus"></i> Tambah Parameter
    </a>
</div>

{{-- 5C PARAMETER CARDS --}}
<div class="space-y-6">
@foreach($meta as $key => $m)
@php
    $c        = $colorMap[$m['color']];
    $paramList = $parameters[$key] ?? collect();
@endphp

<div class="card overflow-hidden" id="{{ $key }}">
    {{-- Header --}}
    <div class="bg-gradient-to-r {{ $c['header'] }} border-b {{ $c['border'] }} px-6 py-4">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl {{ $c['badge'] }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ $m['kode'] }}
                </div>
                <div>
                    <h3 class="font-bold {{ $c['title'] }} text-sm">{{ $m['label'] }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $m['deskripsi'] }}</p>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="text-xs text-slate-400 mb-1">Satuan: <span class="font-medium text-slate-600">{{ $m['satuan'] }}</span></div>
                <div class="text-xs {{ $c['himpunan_bg'] }} px-2 py-0.5 rounded-full inline-block">{{ $m['panduan'] }}</div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    @if($paramList->count() > 0)

    {{-- Visual fungsi keanggotaan --}}
    <div class="px-6 pt-5 pb-2">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Visualisasi Fungsi Keanggotaan</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach($paramList as $param)
            @php
                $tf = $tipeFungsiLabel[$param->tipe_fungsi] ?? ['label'=>$param->tipe_fungsi,'icon'=>'fa-wave-square','color'=>'text-slate-500 bg-slate-50'];
                // Format angka
                $fmt = fn($v) => $v >= 1000000 ? 'Rp ' . number_format($v/1000000, 1) . ' jt' : ($v >= 1000 ? number_format($v, 0, ',', '.') : number_format($v, 2));
            @endphp
            <div class="{{ $c['bg'] }} rounded-xl p-4 border {{ $c['border'] }}">
                <div class="flex items-center justify-between mb-3">
                    <span class="{{ $c['himpunan_bg'] }} px-2.5 py-1 rounded-full text-xs font-bold capitalize">
                        {{ $param->himpunan }}
                    </span>
                    <span class="flex items-center gap-1 {{ $tf['color'] }} px-2 py-0.5 rounded-full text-xs font-medium">
                        <i class="fa-solid {{ $tf['icon'] }} text-xs"></i> {{ $tf['label'] }}
                    </span>
                </div>

                {{-- Mini chart SVG --}}
                @php
                    $a = (float)$param->a; $b = (float)$param->b; $c_val = $param->c ? (float)$param->c : null;
                    $maxVal = $c_val ?? $b;
                    $minVal = $a;
                    $range  = $maxVal - $minVal;
                    if($range <= 0) $range = 1;

                    $toX = fn($v) => round(( ($v - $minVal) / $range ) * 100, 1);

                    if($param->tipe_fungsi === 'linear_naik') {
                        $points = "0,40 {$toX($a)},40 {$toX($b)},0 100,0";
                    } elseif($param->tipe_fungsi === 'linear_turun') {
                        $points = "0,0 {$toX($a)},0 {$toX($b)},40 100,40";
                    } else {
                        // segitiga
                        $xA = $toX($a); $xB = $toX($b); $xC = $toX($c_val ?? $b);
                        $points = "0,40 {$xA},40 {$xB},0 {$xC},40 100,40";
                    }
                @endphp
                <div class="mb-3 bg-white rounded-lg p-2">
                    <svg viewBox="0 0 100 50" class="w-full h-10" preserveAspectRatio="none">
                        <polyline points="{{ $points }}" fill="none" stroke="{{ ['blue'=>'#3b82f6','green'=>'#22c55e','amber'=>'#f59e0b','purple'=>'#a855f7','rose'=>'#f43f5e'][$m['color']] }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="{{ $points }} 100,50 0,50" fill="{{ ['blue'=>'#dbeafe','green'=>'#dcfce7','amber'=>'#fef3c7','purple'=>'#f3e8ff','rose'=>'#ffe4e6'][$m['color']] }}" stroke="none" opacity="0.5"/>
                    </svg>
                </div>

                {{-- Parameter values --}}
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 font-medium">Titik a</span>
                        <span class="font-mono font-semibold text-slate-700">{{ $fmt($param->a) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 font-medium">Titik b</span>
                        <span class="font-mono font-semibold text-slate-700">{{ $fmt($param->b) }}</span>
                    </div>
                    @if($param->c !== null)
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500 font-medium">Titik c</span>
                        <span class="font-mono font-semibold text-slate-700">{{ $fmt($param->c) }}</span>
                    </div>
                    @endif
                </div>

                @if($param->keterangan)
                <p class="text-xs text-slate-400 mt-2 pt-2 border-t {{ $c['border'] }}">{{ $param->keterangan }}</p>
                @endif

                {{-- Actions --}}
                <div class="flex gap-2 mt-3 pt-2 border-t {{ $c['border'] }}">
                    <a href="{{ route('admin.parameter.edit', $param->id) }}"
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg bg-white border {{ $c['border'] }} text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        <i class="fa-solid fa-pen text-xs"></i> Edit
                    </a>
                    <form action="{{ route('admin.parameter.destroy', $param->id) }}" method="POST"
                        onsubmit="return confirm('Hapus parameter {{ $param->himpunan }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-white border border-red-200 text-red-500 hover:bg-red-50 text-xs font-medium transition-colors">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Detail tabel --}}
    <div class="px-6 pb-5 mt-3">
        <details class="group">
            <summary class="text-xs font-semibold text-slate-400 cursor-pointer hover:text-slate-600 flex items-center gap-2 select-none">
                <i class="fa-solid fa-chevron-right text-xs group-open:rotate-90 transition-transform"></i>
                Lihat tabel detail
            </summary>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="text-left px-4 py-2.5 font-semibold text-slate-500 uppercase tracking-wider">Himpunan</th>
                            <th class="text-left px-4 py-2.5 font-semibold text-slate-500 uppercase tracking-wider">Tipe Fungsi</th>
                            <th class="text-center px-4 py-2.5 font-semibold text-slate-500 uppercase tracking-wider">a</th>
                            <th class="text-center px-4 py-2.5 font-semibold text-slate-500 uppercase tracking-wider">b</th>
                            <th class="text-center px-4 py-2.5 font-semibold text-slate-500 uppercase tracking-wider">c</th>
                            <th class="text-center px-4 py-2.5 font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($paramList as $param)
                        <tr class="table-row">
                            <td class="px-4 py-2.5">
                                <span class="{{ $c['himpunan_bg'] }} px-2 py-0.5 rounded-full text-xs font-semibold capitalize">{{ $param->himpunan }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-slate-600">{{ str_replace('_', ' ', ucfirst($param->tipe_fungsi)) }}</td>
                            <td class="px-4 py-2.5 text-center font-mono text-slate-700">{{ number_format($param->a, 2, '.', '') }}</td>
                            <td class="px-4 py-2.5 text-center font-mono text-slate-700">{{ number_format($param->b, 2, '.', '') }}</td>
                            <td class="px-4 py-2.5 text-center font-mono text-slate-400">{{ $param->c !== null ? number_format($param->c, 2, '.', '') : '—' }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $param->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $param->is_active ? 'bg-green-600' : 'bg-slate-400' }}"></span>
                                    {{ $param->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    </div>

    @else
    {{-- Empty state --}}
    <div class="px-6 py-10 text-center">
        <div class="w-14 h-14 rounded-2xl {{ $c['icon_bg'] }} flex items-center justify-center mx-auto mb-3">
            <i class="fa-solid {{ $m['icon'] }} text-xl"></i>
        </div>
        <p class="font-semibold text-slate-600 text-sm">Belum ada parameter untuk {{ $m['label'] }}</p>
        <p class="text-xs text-slate-400 mt-1 mb-4">Tambahkan fungsi keanggotaan fuzzy untuk kriteria ini</p>
        <a href="{{ route('admin.parameter.create', ['kategori' => $key]) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl {{ $c['badge'] }} text-white text-xs font-semibold hover:opacity-90 transition-opacity">
            <i class="fa-solid fa-plus"></i> Tambah Parameter {{ $m['kode'] }}
        </a>
    </div>
    @endif
</div>

@endforeach
</div>

{{-- Info box --}}
<div class="card p-5 bg-slate-50">
    <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
            <i class="fa-solid fa-circle-info text-indigo-600 text-sm"></i>
        </div>
        <div>
            <p class="font-semibold text-slate-700 text-sm mb-2">Panduan Tipe Fungsi Keanggotaan</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-slate-600">
                <div class="flex items-start gap-2">
                    <span class="flex items-center gap-1 text-green-600 bg-green-50 px-2 py-0.5 rounded-full font-medium flex-shrink-0"><i class="fa-solid fa-arrow-trend-up text-xs"></i> Linear Naik</span>
                    <span>Nilai meningkat dari a (μ=0) ke b (μ=1). Digunakan untuk himpunan "tinggi/baik/besar".</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="flex items-center gap-1 text-red-500 bg-red-50 px-2 py-0.5 rounded-full font-medium flex-shrink-0"><i class="fa-solid fa-arrow-trend-down text-xs"></i> Linear Turun</span>
                    <span>Nilai menurun dari a (μ=1) ke b (μ=0). Digunakan untuk himpunan "rendah/buruk/kecil".</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="flex items-center gap-1 text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-medium flex-shrink-0"><i class="fa-solid fa-caret-up text-xs"></i> Segitiga</span>
                    <span>Naik dari a ke b (puncak μ=1) lalu turun ke c. Digunakan untuk himpunan "sedang/cukup".</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
