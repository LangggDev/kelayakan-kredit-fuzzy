@extends('layouts.app')
@section('title', 'Analisis Baru 5C')
@section('page-title', 'Analisis Kelayakan Kredit — Metode 5C')
@section('page-subtitle', 'Character · Capacity · Capital · Collateral · Condition')

@section('content')
@if($errors->any())
<div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
    <div class="font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Terdapat kesalahan input:</div>
    <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('analis.store') }}" method="POST" id="analisisForm">
@csrf

{{-- MODE NASABAH --}}
<div class="card p-6 mb-6">
    <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">1</span>
        Pilih Mode Input Nasabah
    </h3>
    <div class="grid grid-cols-2 gap-3">
        <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-4 hover:border-indigo-400 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
            <input type="radio" name="mode" value="baru" class="sr-only" checked onchange="switchMode('baru')">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center"><i class="fa-solid fa-user-plus text-indigo-600 text-sm"></i></div>
                <div><div class="font-semibold text-slate-700 text-sm">Nasabah Baru</div><div class="text-xs text-slate-400">Input data nasabah baru</div></div>
            </div>
        </label>
        <label class="cursor-pointer border-2 border-slate-200 rounded-xl p-4 hover:border-indigo-400 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
            <input type="radio" name="mode" value="existing" class="sr-only" onchange="switchMode('existing')">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-violet-100 flex items-center justify-center"><i class="fa-solid fa-user-check text-violet-600 text-sm"></i></div>
                <div><div class="font-semibold text-slate-700 text-sm">Nasabah Terdaftar</div><div class="text-xs text-slate-400">Pilih dari data yang ada</div></div>
            </div>
        </label>
    </div>
</div>

{{-- DATA NASABAH --}}
<div class="card p-6 mb-6">
    <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">2</span>
        Data Calon Nasabah
    </h3>
    <div id="existingSection" class="hidden">
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Nasabah Terdaftar</label>
        <select name="calon_nasabah_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50">
            <option value="">-- Pilih Nasabah --</option>
            @foreach($nasabahList as $n)
            <option value="{{ $n->id }}" {{ old('calon_nasabah_id')==$n->id?'selected':'' }}>{{ $n->nama }} ({{ $n->nik }})</option>
            @endforeach
        </select>
    </div>
    <div id="baruSection">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"></div>
            <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"></div>
            <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">Pekerjaan / Usaha</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"></div>
            <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"></div>
            <div class="md:col-span-2"><label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white resize-none">{{ old('alamat') }}</textarea></div>
        </div>
    </div>
</div>

{{-- 5C PARAMETERS --}}
<div class="card p-6 mb-6">
    <h3 class="font-semibold text-slate-800 mb-1 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">3</span>
        Parameter Penilaian 5C
    </h3>
    <p class="text-sm text-slate-400 mb-6 ml-9">Isi semua parameter berdasarkan 5 kriteria kelayakan kredit</p>

    <div class="space-y-5">

        {{-- C1: CHARACTER --}}
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">C1</div>
                <div>
                    <div class="font-semibold text-slate-800 text-sm">Character — Karakter Nasabah</div>
                    <div class="text-xs text-slate-500">Riwayat kredit berdasarkan BI Checking / SLIK OJK (skor 0–100)</div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Skor Kredit (BI Checking) <span class="text-red-500">*</span></label>
                        <input type="number" name="skor_kredit" id="skorKredit" value="{{ old('skor_kredit', 75) }}"
                            min="0" max="100" step="1"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                            oninput="updateCharLabel(this.value)" required>
                        <p class="text-xs text-slate-400 mt-1">0 = Sangat Buruk, 100 = Sempurna</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-slate-500 mb-2">Panduan Skor</p>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between"><span class="text-red-600 font-medium">0 – 40</span><span class="text-slate-600">Buruk (kolektibilitas 3,4,5)</span></div>
                            <div class="flex justify-between"><span class="text-yellow-600 font-medium">41 – 60</span><span class="text-slate-600">Cukup (kolektibilitas 2)</span></div>
                            <div class="flex justify-between"><span class="text-green-600 font-medium">61 – 100</span><span class="text-slate-600">Baik (kolektibilitas 1)</span></div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-blue-200">
                            <span class="text-xs font-semibold text-blue-700" id="charLabel">Baik ✓</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- C2: CAPACITY --}}
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">C2</div>
                <div>
                    <div class="font-semibold text-slate-800 text-sm">Capacity — Kemampuan Membayar</div>
                    <div class="text-xs text-slate-500">Rasio cicilan terhadap penghasilan dihitung otomatis</div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Penghasilan per Bulan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                            <input type="number" name="penghasilan" id="penghasilan" value="{{ old('penghasilan') }}"
                                min="1" class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                oninput="hitungRasio()" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Pinjaman <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                            <input type="number" name="jumlah_pinjaman" id="pinjaman" value="{{ old('jumlah_pinjaman') }}"
                                min="1" class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                oninput="hitungRasio(); hitungLTV()" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jangka Waktu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="jangka_waktu" id="jangkaWaktu" value="{{ old('jangka_waktu') }}"
                                min="1" max="360" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                oninput="hitungRasio()" required>
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">bulan</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 bg-green-50 rounded-xl p-4 flex items-center gap-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Estimasi Cicilan/Bulan</p>
                        <p class="text-lg font-bold text-slate-800" id="estimasiCicilan">Rp –</p>
                    </div>
                    <div class="w-px h-10 bg-green-200"></div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Rasio Cicilan / Penghasilan</p>
                        <p class="text-lg font-bold" id="rasioLabel">–</p>
                    </div>
                    <div class="w-px h-10 bg-green-200"></div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Kapasitas</p>
                        <p class="text-sm font-semibold" id="capacityLabel">–</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- C3: CAPITAL --}}
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">C3</div>
                <div>
                    <div class="font-semibold text-slate-800 text-sm">Capital — Modal / Kekayaan Bersih</div>
                    <div class="text-xs text-slate-500">Total aset dikurangi total kewajiban/hutang nasabah</div>
                </div>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Total Aset <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                        <input type="number" name="total_aset" id="totalAset" value="{{ old('total_aset') }}"
                            min="0" class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                            oninput="hitungAsetBersih()">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Total Hutang / Kewajiban</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                        <input type="number" name="total_hutang" id="totalHutang" value="{{ old('total_hutang', 0) }}"
                            min="0" class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                            oninput="hitungAsetBersih()">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Aset Bersih (Otomatis) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                        <input type="number" name="aset_bersih" id="asetBersih" value="{{ old('aset_bersih') }}"
                            class="w-full pl-10 pr-3 py-2.5 border border-amber-300 bg-amber-50 rounded-xl text-sm font-semibold" readonly required>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Dihitung otomatis: Total Aset − Total Hutang</p>
                </div>
            </div>
        </div>

        {{-- C4: COLLATERAL --}}
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">C4</div>
                <div>
                    <div class="font-semibold text-slate-800 text-sm">Collateral — Agunan / Jaminan</div>
                    <div class="text-xs text-slate-500">Nilai aset jaminan yang diserahkan sebagai agunan</div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nilai Agunan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                            <input type="number" name="nilai_agunan" id="nilaiAgunan" value="{{ old('nilai_agunan') }}"
                                min="0" class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                oninput="hitungLTV()" required>
                        </div>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4">
                        <p class="text-xs text-slate-500 font-medium mb-1">LTV Ratio (Loan to Value)</p>
                        <p class="text-xl font-bold text-purple-700" id="ltvLabel">–</p>
                        <p class="text-xs text-slate-500 mt-1" id="ltvKet">Pinjaman ÷ Agunan × 100%</p>
                        <div class="mt-1"><span class="text-xs font-semibold" id="collateralLabel">–</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- C5: CONDITION --}}
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-rose-50 to-pink-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">C5</div>
                <div>
                    <div class="font-semibold text-slate-800 text-sm">Condition — Kondisi Ekonomi</div>
                    <div class="text-xs text-slate-500">Penilaian kondisi ekonomi makro dan sektor usaha nasabah</div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Skor Kondisi Ekonomi <span class="text-red-500">*</span></label>
                        <input type="range" id="kondisiRange" min="0" max="100" value="{{ old('kondisi_ekonomi', 60) }}"
                            class="w-full accent-rose-500" oninput="updateCondisi(this.value)">
                        <div class="flex justify-between text-xs text-slate-400 mt-1"><span>0 (Buruk)</span><span>50 (Cukup)</span><span>100 (Baik)</span></div>
                        <input type="number" name="kondisi_ekonomi" id="kondisiInput" value="{{ old('kondisi_ekonomi', 60) }}"
                            min="0" max="100" class="mt-2 w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                            oninput="document.getElementById('kondisiRange').value=this.value; updateCondisi(this.value)">
                    </div>
                    <div class="bg-rose-50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-slate-500 mb-2">Panduan Penilaian</p>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between"><span class="text-red-600 font-medium">0 – 40</span><span class="text-slate-600">Ekonomi lesu / resesi</span></div>
                            <div class="flex justify-between"><span class="text-yellow-600 font-medium">41 – 60</span><span class="text-slate-600">Kondisi normal / stabil</span></div>
                            <div class="flex justify-between"><span class="text-green-600 font-medium">61 – 100</span><span class="text-slate-600">Ekonomi tumbuh / booming</span></div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-rose-200">
                            <span class="text-sm font-bold text-rose-700" id="condLabel">Baik ✓</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CATATAN & SUBMIT --}}
<div class="card p-6">
    <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">4</span>
        Catatan Tambahan
    </h3>
    <textarea name="catatan" rows="3" placeholder="Catatan atau keterangan tambahan (opsional)..."
        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white resize-none">{{ old('catatan') }}</textarea>
    <div class="flex gap-3 mt-5">
        <button type="submit" id="submitBtn" class="btn-primary flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold text-sm">
            <i class="fa-solid fa-calculator"></i> Proses Analisis Fuzzy 5C
        </button>
        <a href="{{ route('analis.dashboard') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">Batal</a>
    </div>
</div>
</form>

<script>
function switchMode(mode) {
    document.getElementById('baruSection').classList.toggle('hidden', mode !== 'baru');
    document.getElementById('existingSection').classList.toggle('hidden', mode === 'baru');
}

// C1 Character
function updateCharLabel(v) {
    const el = document.getElementById('charLabel');
    v = parseFloat(v);
    if (v <= 40) el.textContent = '⚠️ Buruk — Risiko Tinggi', el.className = 'text-xs font-semibold text-red-600';
    else if (v <= 60) el.textContent = '🟡 Cukup — Perlu Dipertimbangkan', el.className = 'text-xs font-semibold text-yellow-600';
    else el.textContent = '✅ Baik — Layak', el.className = 'text-xs font-semibold text-green-600';
}

// C2 Capacity: hitung cicilan & rasio
function hitungRasio() {
    const pinjaman = parseFloat(document.getElementById('pinjaman').value) || 0;
    const penghasilan = parseFloat(document.getElementById('penghasilan').value) || 0;
    const jangka = parseInt(document.getElementById('jangkaWaktu').value) || 0;
    if (!pinjaman || !penghasilan || !jangka) return;

    const r = 0.12 / 12;
    const cicilan = pinjaman * (r * Math.pow(1+r, jangka)) / (Math.pow(1+r, jangka) - 1);
    const rasio = (cicilan / penghasilan) * 100;

    document.getElementById('estimasiCicilan').textContent = 'Rp ' + Math.round(cicilan).toLocaleString('id-ID');
    const rasioEl = document.getElementById('rasioLabel');
    rasioEl.textContent = rasio.toFixed(1) + '%';

    const capEl = document.getElementById('capacityLabel');
    if (rasio <= 30) { rasioEl.className = 'text-lg font-bold text-green-600'; capEl.textContent = '✅ Tinggi'; capEl.className = 'text-sm font-semibold text-green-600'; }
    else if (rasio <= 50) { rasioEl.className = 'text-lg font-bold text-yellow-600'; capEl.textContent = '🟡 Sedang'; capEl.className = 'text-sm font-semibold text-yellow-600'; }
    else { rasioEl.className = 'text-lg font-bold text-red-600'; capEl.textContent = '⚠️ Rendah'; capEl.className = 'text-sm font-semibold text-red-600'; }
}

// C3 Capital: hitung aset bersih
function hitungAsetBersih() {
    const aset = parseFloat(document.getElementById('totalAset').value) || 0;
    const hutang = parseFloat(document.getElementById('totalHutang').value) || 0;
    document.getElementById('asetBersih').value = aset - hutang;
}

// C4 Collateral: hitung LTV
function hitungLTV() {
    const pinjaman = parseFloat(document.getElementById('pinjaman').value) || 0;
    const agunan = parseFloat(document.getElementById('nilaiAgunan').value) || 0;
    const ltvEl = document.getElementById('ltvLabel');
    const ketEl = document.getElementById('ltvKet');
    const collEl = document.getElementById('collateralLabel');
    if (!agunan || !pinjaman) { ltvEl.textContent = '–'; return; }

    const ltv = (pinjaman / agunan) * 100;
    ltvEl.textContent = ltv.toFixed(1) + '%';
    ketEl.textContent = `Rp ${pinjaman.toLocaleString('id-ID')} ÷ Rp ${agunan.toLocaleString('id-ID')} × 100`;

    if (ltv < 70) { ltvEl.className = 'text-xl font-bold text-green-600'; collEl.textContent = '✅ Agunan Kuat (LTV < 70%)'; collEl.className = 'text-xs font-semibold text-green-600'; }
    else if (ltv <= 110) { ltvEl.className = 'text-xl font-bold text-yellow-600'; collEl.textContent = '🟡 Agunan Cukup (LTV 70-110%)'; collEl.className = 'text-xs font-semibold text-yellow-600'; }
    else { ltvEl.className = 'text-xl font-bold text-red-600'; collEl.textContent = '⚠️ Agunan Lemah (LTV > 110%)'; collEl.className = 'text-xs font-semibold text-red-600'; }
}

// C5 Condition
function updateCondisi(v) {
    v = parseFloat(v);
    document.getElementById('kondisiInput').value = v;
    document.getElementById('kondisiRange').value = v;
    const el = document.getElementById('condLabel');
    if (v <= 40) el.textContent = '⚠️ Buruk — Ekonomi Lesu', el.className = 'text-sm font-bold text-red-600';
    else if (v <= 60) el.textContent = '🟡 Cukup — Kondisi Normal', el.className = 'text-sm font-bold text-yellow-600';
    else el.textContent = '✅ Baik — Ekonomi Tumbuh', el.className = 'text-sm font-bold text-green-600';
}

// Init
updateCharLabel(document.getElementById('skorKredit').value);
updateCondisi(document.getElementById('kondisiInput').value);
switchMode('{{ old('mode', 'baru') }}');

document.getElementById('analisisForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;
});
</script>
@endsection
