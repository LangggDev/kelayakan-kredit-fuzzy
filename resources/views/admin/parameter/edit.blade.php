@extends('layouts.app')
@section('title', 'Edit Parameter')
@section('page-title', 'Edit Parameter Fuzzy')
@section('page-subtitle', 'Perbarui fungsi keanggotaan — ' . ucfirst($parameter->kategori_5c) . ' / ' . ucfirst($parameter->himpunan))

@section('content')
<div class="w-full">
    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
        <ul class="space-y-1">@foreach($errors->all() as $e)<li class="flex gap-2"><i class="fa-solid fa-circle-xmark mt-0.5 flex-shrink-0"></i>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Info badge --}}
    @php
    $currentMeta = $meta[$parameter->kategori_5c] ?? null;
    $badgeColors = ['blue'=>'#2563eb','green'=>'#16a34a','amber'=>'#d97706','purple'=>'#9333ea','rose'=>'#e11d48'];
    @endphp
    @if($currentMeta)
    <div class="flex items-center gap-3 p-4 rounded-xl border-2 border-indigo-200 bg-indigo-50 mb-5">
        <span class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
            style="background:{{ $badgeColors[$currentMeta['color']] ?? '#6366f1' }}">{{ $currentMeta['kode'] }}</span>
        <div>
            <div class="font-semibold text-slate-800 text-sm">{{ $currentMeta['label'] }}</div>
            <div class="text-xs text-slate-500">{{ $currentMeta['panduan'] }}</div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.parameter.update', $parameter->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="card p-6 space-y-5">
            <h3 class="font-semibold text-slate-800 border-b border-slate-100 pb-3">Edit Parameter</h3>

            {{-- Kriteria 5C --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kriteria 5C <span class="text-red-500">*</span></label>
                <select name="kategori_5c" id="kategoriSelect" onchange="updateMeta()"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                    @foreach($meta as $key => $m)
                    <option value="{{ $key }}" {{ old('kategori_5c', $parameter->kategori_5c)===$key?'selected':'' }}>
                        {{ $m['kode'] }} — {{ explode(' — ', $m['label'])[1] ?? $m['label'] }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1" id="panduan5c">{{ $currentMeta['panduan'] ?? '' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Himpunan <span class="text-red-500">*</span></label>
                    <input type="text" name="himpunan" value="{{ old('himpunan', $parameter->himpunan) }}"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                    <p class="text-xs text-slate-400 mt-1" id="saranHimpunan">
                        Saran: {{ $currentMeta ? implode(', ', $currentMeta['himpunan']) : '' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Fungsi <span class="text-red-500">*</span></label>
                    <select name="tipe_fungsi" id="tipeFungsi" onchange="updateTipeInfo()"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                        <option value="linear_turun" {{ old('tipe_fungsi',$parameter->tipe_fungsi)==='linear_turun'?'selected':'' }}>📉 Linear Turun</option>
                        <option value="segitiga"     {{ old('tipe_fungsi',$parameter->tipe_fungsi)==='segitiga'    ?'selected':'' }}>🔺 Segitiga</option>
                        <option value="linear_naik"  {{ old('tipe_fungsi',$parameter->tipe_fungsi)==='linear_naik' ?'selected':'' }}>📈 Linear Naik</option>
                    </select>
                </div>
            </div>

            <div id="tipeInfo" class="hidden p-3 rounded-xl text-xs border">
                <i class="fa-solid fa-circle-info mr-1"></i><span id="tipeInfoText"></span>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Titik Parameter</label>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase" id="labelA">Titik a</label>
                        <input type="number" name="a" value="{{ old('a', $parameter->a) }}" step="any"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white font-mono" required>
                        <p class="text-xs text-slate-400 mt-1" id="hintA"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase" id="labelB">Titik b</label>
                        <input type="number" name="b" value="{{ old('b', $parameter->b) }}" step="any"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white font-mono" required>
                        <p class="text-xs text-slate-400 mt-1" id="hintB"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase" id="labelC">Titik c</label>
                        <input type="number" name="c" value="{{ old('c', $parameter->c) }}" step="any" id="inputC"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white font-mono">
                        <p class="text-xs text-slate-400 mt-1" id="hintC"></p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan', $parameter->keterangan) }}"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="isActive"
                    {{ old('is_active', $parameter->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600">
                <label for="isActive" class="text-sm font-medium text-slate-700 cursor-pointer">Parameter Aktif</label>
            </div>
        </div>

        <div class="flex gap-3 mt-5">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.parameter.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>

<script>
const metaAll = @json(collect($meta)->map(fn($m) => ['panduan'=>$m['panduan'],'himpunan'=>$m['himpunan']]));

const tipeHints = {
    'linear_turun': { info:'μ=1 di titik a turun ke μ=0 di titik b. Untuk himpunan rendah/buruk/kecil.', color:'bg-red-50 border-red-200 text-red-700', a:'Titik mulai (μ=1)', b:'Titik akhir (μ=0)', c:'Tidak diperlukan', labelA:'Titik a (μ=1)', labelB:'Titik b (μ=0)' },
    'segitiga':     { info:'Naik dari a ke b (μ=1), turun ke c (μ=0). Untuk himpunan sedang/cukup.', color:'bg-indigo-50 border-indigo-200 text-indigo-700', a:'Kaki kiri (μ=0)', b:'Puncak (μ=1)', c:'Kaki kanan (μ=0)', labelA:'Titik a (kiri)', labelB:'Titik b (puncak)' },
    'linear_naik':  { info:'μ=0 di titik a naik ke μ=1 di titik b. Untuk himpunan tinggi/baik/besar.', color:'bg-green-50 border-green-200 text-green-700', a:'Titik mulai (μ=0)', b:'Titik akhir (μ=1)', c:'Tidak diperlukan', labelA:'Titik a (μ=0)', labelB:'Titik b (μ=1)' },
};

function updateMeta() {
    const key = document.getElementById('kategoriSelect').value;
    if (metaAll[key]) {
        document.getElementById('panduan5c').textContent = metaAll[key].panduan;
        document.getElementById('saranHimpunan').textContent = 'Saran: ' + metaAll[key].himpunan.join(', ');
    }
}

function updateTipeInfo() {
    const tipe = document.getElementById('tipeFungsi').value;
    const el = document.getElementById('tipeInfo');
    if (!tipe) { el.classList.add('hidden'); return; }
    const h = tipeHints[tipe];
    el.className = 'p-3 rounded-xl text-xs border ' + h.color;
    document.getElementById('tipeInfoText').textContent = h.info;
    el.classList.remove('hidden');
    document.getElementById('hintA').textContent = h.a;
    document.getElementById('hintB').textContent = h.b;
    document.getElementById('hintC').textContent = h.c;
    document.getElementById('labelA').textContent = h.labelA || 'Titik a';
    document.getElementById('labelB').textContent = h.labelB || 'Titik b';
    document.getElementById('inputC').required = (tipe === 'segitiga');
}
updateTipeInfo();
</script>
@endsection
