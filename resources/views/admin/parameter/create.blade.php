@extends('layouts.app')
@section('title', 'Tambah Parameter')
@section('page-title', 'Tambah Parameter Fuzzy 5C')
@section('page-subtitle', 'Tambah fungsi keanggotaan baru untuk salah satu kriteria 5C')

@section('content')
<div class="w-full">
    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-5">
        <ul class="space-y-1">@foreach($errors->all() as $e)<li class="flex gap-2"><i class="fa-solid fa-circle-xmark mt-0.5 flex-shrink-0"></i>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    @php
    $colorMap = ['blue'=>'border-blue-300 bg-blue-50','green'=>'border-green-300 bg-green-50','amber'=>'border-amber-300 bg-amber-50','purple'=>'border-purple-300 bg-purple-50','rose'=>'border-rose-300 bg-rose-50'];
    @endphp

    <form action="{{ route('admin.parameter.store') }}" method="POST" id="paramForm">
        @csrf
        <div class="card p-6 space-y-5">
            <h3 class="font-semibold text-slate-800 border-b border-slate-100 pb-3">Konfigurasi Parameter</h3>

            {{-- Pilih Kriteria 5C --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kriteria 5C <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2" id="kategoriGrid">
                    @foreach($meta as $key => $m)
                    @php $c = $colorMap[$m['color']]; @endphp
                    <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-3 hover:border-indigo-400 transition-all has-[:checked]:{{ $c }} has-[:checked]:border-opacity-100" >
                        <input type="radio" name="kategori_5c" value="{{ $key }}" class="sr-only"
                            {{ old('kategori_5c', request('kategori')) === $key ? 'checked' : '' }}
                            onchange="updateKategori('{{ $key }}', {{ json_encode($m) }})">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-{{ $m['color'] }}-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0" style="background:{{ ['blue'=>'#2563eb','green'=>'#16a34a','amber'=>'#d97706','purple'=>'#9333ea','rose'=>'#e11d48'][$m['color']] }}">{{ $m['kode'] }}</span>
                            <div>
                                <div class="font-semibold text-slate-700 text-xs">{{ $m['kode'] }}</div>
                                <div class="text-xs text-slate-400">{{ explode(' — ', $m['label'])[1] ?? '' }}</div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                <div id="kategoriInfo" class="mt-3 p-3 bg-indigo-50 rounded-xl text-xs text-indigo-700 hidden">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    <span id="kategoriInfoText"></span>
                </div>
            </div>

            {{-- Nama Himpunan --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Himpunan <span class="text-red-500">*</span></label>
                    <input type="text" name="himpunan" value="{{ old('himpunan') }}" id="himpunanInput"
                        placeholder="e.g. baik, cukup, buruk"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                    <p class="text-xs text-slate-400 mt-1" id="himpunanSaran"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Fungsi <span class="text-red-500">*</span></label>
                    <select name="tipe_fungsi" id="tipeFungsi" onchange="updateTipeInfo()"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white" required>
                        <option value="">Pilih tipe...</option>
                        <option value="linear_turun"  {{ old('tipe_fungsi')==='linear_turun' ?'selected':'' }}>📉 Linear Turun (rendah/buruk/kecil)</option>
                        <option value="segitiga"      {{ old('tipe_fungsi')==='segitiga'     ?'selected':'' }}>🔺 Segitiga (sedang/cukup)</option>
                        <option value="linear_naik"   {{ old('tipe_fungsi')==='linear_naik'  ?'selected':'' }}>📈 Linear Naik (tinggi/baik/besar)</option>
                    </select>
                </div>
            </div>

            {{-- Info tipe fungsi --}}
            <div id="tipeInfo" class="hidden p-3 rounded-xl text-xs border">
                <i class="fa-solid fa-circle-info mr-1"></i><span id="tipeInfoText"></span>
            </div>

            {{-- Parameter a, b, c --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Titik Parameter Fungsi <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider" id="labelA">Titik a</label>
                        <input type="number" name="a" value="{{ old('a') }}" step="any"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white font-mono" required>
                        <p class="text-xs text-slate-400 mt-1" id="hintA"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider" id="labelB">Titik b</label>
                        <input type="number" name="b" value="{{ old('b') }}" step="any"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white font-mono" required>
                        <p class="text-xs text-slate-400 mt-1" id="hintB"></p>
                    </div>
                    <div id="cField">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Titik c <span class="text-slate-300">(opsional)</span></label>
                        <input type="number" name="c" value="{{ old('c') }}" step="any" id="inputC"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white font-mono">
                        <p class="text-xs text-slate-400 mt-1" id="hintC">Hanya untuk fungsi segitiga</p>
                    </div>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                    placeholder="Deskripsi singkat himpunan ini..."
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active',true)?'checked':'' }} class="rounded border-slate-300 text-indigo-600">
                <label for="isActive" class="text-sm font-medium text-slate-700 cursor-pointer">Parameter Aktif</label>
            </div>
        </div>

        <div class="flex gap-3 mt-5">
            <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-white font-semibold text-sm flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan Parameter
            </button>
            <a href="{{ route('admin.parameter.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">Batal</a>
        </div>
    </form>
</div>
@endsection
<script>
const tipeHints = {
    'linear_turun': {
        info: 'Fungsi menurun: μ=1 saat nilai ≤ a, turun linear sampai μ=0 saat nilai ≥ b. Cocok untuk himpunan "rendah/buruk/kecil".',
        color: 'bg-red-50 border-red-200 text-red-700',
        a: 'Titik mulai (μ = 1)', b: 'Titik akhir (μ = 0)', c: '',
        labelA: 'Titik a (μ=1)', labelB: 'Titik b (μ=0)',
    },
    'segitiga': {
        info: 'Fungsi segitiga: μ naik dari a ke b (puncak μ=1) lalu turun ke c (μ=0). Wajib isi titik c.',
        color: 'bg-indigo-50 border-indigo-200 text-indigo-700',
        a: 'Kaki kiri (μ=0)', b: 'Puncak (μ=1)', c: 'Kaki kanan (μ=0)',
        labelA: 'Titik a (kiri)', labelB: 'Titik b (puncak)', labelC: 'Titik c (kanan)',
    },
    'linear_naik': {
        info: 'Fungsi menaik: μ=0 saat nilai ≤ a, naik linear sampai μ=1 saat nilai ≥ b. Cocok untuk himpunan "tinggi/baik/besar".',
        color: 'bg-green-50 border-green-200 text-green-700',
        a: 'Titik mulai (μ = 0)', b: 'Titik akhir (μ = 1)', c: '',
        labelA: 'Titik a (μ=0)', labelB: 'Titik b (μ=1)',
    },
};

const metaSaran = @json(collect($meta)->map(fn($m) => ['saran' => implode(', ', $m['himpunan'] ?? [])]));

function updateKategori(key, m) {
    const el = document.getElementById('kategoriInfo');
    const txt = document.getElementById('kategoriInfoText');
    el.classList.remove('hidden');
    txt.textContent = m.panduan;
    const saran = document.getElementById('himpunanSaran');
    saran.textContent = 'Saran: ' + metaSaran[key].saran;
}

function updateTipeInfo() {
    const tipe = document.getElementById('tipeFungsi').value;
    const el = document.getElementById('tipeInfo');
    const txt = document.getElementById('tipeInfoText');
    const cField = document.getElementById('cField');
    const inputC = document.getElementById('inputC');

    if (!tipe) { el.classList.add('hidden'); return; }
    const h = tipeHints[tipe];
    el.className = 'p-3 rounded-xl text-xs border ' + h.color;
    txt.textContent = h.info;
    el.classList.remove('hidden');

    document.getElementById('hintA').textContent = h.a;
    document.getElementById('hintB').textContent = h.b;
    document.getElementById('labelA').textContent = h.labelA || 'Titik a';
    document.getElementById('labelB').textContent = h.labelB || 'Titik b';

    if (tipe === 'segitiga') {
        inputC.required = true;
        document.getElementById('hintC').textContent = h.c;
        cField.querySelector('label').innerHTML = 'Titik c <span class="text-red-500">*</span>';
    } else {
        inputC.required = false;
        document.getElementById('hintC').textContent = 'Tidak diperlukan';
        cField.querySelector('label').innerHTML = 'Titik c <span class="text-slate-300">(opsional)</span>';
    }
}

// Init from old values
const oldKat = '{{ old('kategori_5c', request('kategori')) }}';
if (oldKat && metaSaran[oldKat]) {
    document.getElementById('himpunanSaran').textContent = 'Saran: ' + metaSaran[oldKat].saran;
}
updateTipeInfo();
</script>

