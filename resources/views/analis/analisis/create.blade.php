@extends('layouts.app')
@section('title', 'Analisis Baru 5C')
@section('page-title', 'Analisis Kelayakan Kredit — Metode 5C')

@section('content')
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            <div class="font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Terdapat kesalahan input:</div>
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('analis.store') }}" method="POST" id="analisisForm">
        @csrf

        {{-- MODE Debitur --}}
        <div class="card p-6 mb-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span
                    class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">1</span>
                Pilih Mode Input Debitur
            </h3>
            <div class="grid grid-cols-2 gap-3">
                <label
                    class="cursor-pointer border-2 border-slate-200 rounded-xl p-4 hover:border-indigo-400 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                    <input type="radio" name="mode" value="baru" class="sr-only" checked onchange="switchMode('baru')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center"><i
                                class="fa-solid fa-user-plus text-indigo-600 text-sm"></i></div>
                        <div>
                            <div class="font-semibold text-slate-700 text-sm">Debitur Baru</div>
                            <div class="text-xs text-slate-400">Input data debitur baru</div>
                        </div>
                    </div>
                </label>
                <label
                    class="cursor-pointer border-2 border-slate-200 rounded-xl p-4 hover:border-indigo-400 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                    <input type="radio" name="mode" value="existing" class="sr-only" onchange="switchMode('existing')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-violet-100 flex items-center justify-center"><i
                                class="fa-solid fa-user-check text-violet-600 text-sm"></i></div>
                        <div>
                            <div class="font-semibold text-slate-700 text-sm">Debitur Terdaftar</div>
                            <div class="text-xs text-slate-400">Pilih dari data yang ada</div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- DATA DEBITUR --}}
        <div class="card p-6 mb-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span
                    class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">2</span>
                Data Calon Debitur
            </h3>
            <div id="existingSection" class="hidden">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Debitur Terdaftar</label>
                <select name="calon_nasabah_id"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50">
                    <option value="">-- Pilih Debitur --</option>
                    @foreach($nasabahList as $n)
                        <option value="{{ $n->id }}" {{ old('calon_nasabah_id') == $n->id ? 'selected' : '' }}>{{ $n->nama }}
                            ({{ $n->nik }})</option>
                    @endforeach
                </select>
            </div>
            <div id="baruSection">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>
                    <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">NIK <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>
                    <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">Pekerjaan / Usaha</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>
                    <div><label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white">
                    </div>
                    <div class="md:col-span-2"><label
                            class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white resize-none">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5C PARAMETERS --}}
        <div class="card p-6 mb-6">
            <h3 class="font-semibold text-slate-800 mb-1 flex items-center gap-2">
                <span
                    class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">3</span>
                Parameter Penilaian 5C
            </h3>
            <p class="text-sm text-slate-400 mb-6 ml-9">Isi semua parameter berdasarkan 5 kriteria kelayakan kredit</p>

            <div class="space-y-5">

                {{-- C1: CHARACTER --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            C1</div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Character — Karakter Debitur</div>
                            <div class="text-xs text-slate-500">Riwayat kredit berdasarkan BI Checking / SLIK OJK</div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe SLIK <span
                                        class="text-red-500">*</span></label>
                                <input type="range" id="slikRange" name="skor_kredit_slik" min="1" max="3"
                                    value="{{ old('skor_kredit_slik', 1) }}" class="w-full accent-blue-600"
                                    oninput="updateSlik(this.value)">
                                <div class="flex justify-between text-xs text-slate-400 mt-1">
                                    <span>1 (Excellent)</span>
                                    <span>2 (Medium)</span>
                                    <span>3 (Bad/Worst)</span>
                                </div>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4">
                                <p class="text-xs font-semibold text-slate-500 mb-2">Keterangan SLIK</p>
                                <div class="mt-2">
                                    <span class="text-sm font-bold text-green-600" id="slikLabel">✅ 1 — Excellent / Very
                                        Good / Good</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- C2: CAPACITY --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-green-50 to-emerald-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-green-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            C2</div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Capacity — Kemampuan Membayar</div>
                            <div class="text-xs text-slate-500">Rasio cicilan terhadap penghasilan dihitung otomatis</div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Penghasilan per Bulan <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                    <input type="number" name="penghasilan" id="penghasilan"
                                        value="{{ old('penghasilan') }}" min="1"
                                        class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                        oninput="hitungRasio()" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Pinjaman <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                    <input type="number" name="jumlah_pinjaman" id="pinjaman"
                                        value="{{ old('jumlah_pinjaman') }}" min="1"
                                        class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                        oninput="hitungRasio(); hitungLTV()" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jangka Waktu <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="jangka_waktu" id="jangkaWaktu"
                                        value="{{ old('jangka_waktu') }}" min="1" max="360"
                                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                        oninput="hitungRasio()" required>
                                    <span
                                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">bulan</span>
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

                        <div class="mt-6 pt-5 border-t border-slate-200">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Skor Capacity (0-100) <span
                                    class="text-red-500">*</span></label>
                            <p class="text-xs text-slate-500 mb-2">Otomatis terhitung berdasarkan cicilan dan penghasilan.
                            </p>
                            <div class="flex items-center gap-4 opacity-75 pointer-events-none">
                                <input type="range" name="capacity" id="capacityRange" min="0" max="100"
                                    value="{{ old('capacity', 0) }}" class="w-full accent-green-600">
                                <input type="number" id="capacityInput" value="{{ old('capacity', 0) }}" min="0" max="100"
                                    class="w-20 px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50 text-center"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- C3: CAPITAL --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-yellow-50 to-amber-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            C3</div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Capital — Modal / Kekayaan Bersih</div>
                            <div class="text-xs text-slate-500">Total aset dikurangi total kewajiban/hutang debitur</div>
                        </div>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Total Aset <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                <input type="number" name="total_aset" id="totalAset" value="{{ old('total_aset') }}"
                                    min="0"
                                    class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                    oninput="hitungAsetBersih()">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Total Hutang /
                                Kewajiban</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                <input type="number" name="total_hutang" id="totalHutang"
                                    value="{{ old('total_hutang', 0) }}" min="0"
                                    class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                    oninput="hitungAsetBersih()">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Aset Bersih (Otomatis)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                <input type="number" id="asetBersih" value="{{ old('aset_bersih') }}"
                                    class="w-full pl-10 pr-3 py-2.5 border border-amber-300 bg-amber-50 rounded-xl text-sm font-semibold"
                                    readonly>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Dihitung otomatis: Total Aset − Total Hutang</p>
                        </div>

                        <div class="md:col-span-2 mt-2 pt-5 border-t border-slate-200">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Skor Capital (0-100) <span
                                    class="text-red-500">*</span></label>
                            <p class="text-xs text-slate-500 mb-2">Otomatis terhitung berdasarkan aset bersih.</p>
                            <div class="flex items-center gap-4 opacity-75 pointer-events-none">
                                <input type="range" name="capital" id="capitalRange" min="0" max="100"
                                    value="{{ old('capital', 0) }}" class="w-full accent-amber-500">
                                <input type="number" id="capitalInput" value="{{ old('capital', 0) }}" min="0" max="100"
                                    class="w-20 px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50 text-center"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- C4: COLLATERAL --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-purple-50 to-violet-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            C4</div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Collateral — Agunan / Jaminan</div>
                            <div class="text-xs text-slate-500">Nilai aset jaminan yang diserahkan sebagai agunan</div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nilai Agunan <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                    <input type="number" name="nilai_agunan" id="nilaiAgunan"
                                        value="{{ old('nilai_agunan') }}" min="0"
                                        class="w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
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

                        <div class="mt-6 pt-5 border-t border-slate-200">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Skor Collateral (0-100) <span
                                    class="text-red-500">*</span></label>
                            <p class="text-xs text-slate-500 mb-2">Otomatis terhitung dari nilai agunan.</p>
                            <div class="flex items-center gap-4 opacity-75 pointer-events-none">
                                <input type="range" name="collateral" id="collateralRange" min="0" max="100"
                                    value="{{ old('collateral', 0) }}" class="w-full accent-purple-600">
                                <input type="number" id="collateralInput" value="{{ old('collateral', 0) }}" min="0"
                                    max="100"
                                    class="w-20 px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50 text-center"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- C5: CONDITION --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-rose-50 to-pink-50 px-5 py-3 border-b border-slate-200 flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            C5</div>
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">Condition — Kondisi Ekonomi</div>
                            <div class="text-xs text-slate-500">Penilaian kondisi ekonomi makro dan sektor usaha debitur
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Skor Kondisi Ekonomi <span
                                        class="text-red-500">*</span></label>
                                <input type="range" id="kondisiRange" name="condition" min="0" max="100"
                                    value="{{ old('condition', 60) }}" class="w-full accent-rose-500"
                                    oninput="updateCondisi(this.value)">
                                <div class="flex justify-between text-xs text-slate-400 mt-1"><span>0 (Buruk)</span><span>50
                                        (Cukup)</span><span>100 (Baik)</span></div>
                                <input type="number" id="kondisiInput" value="{{ old('condition', 60) }}" min="0" max="100"
                                    class="mt-2 w-full px-3.5 py-2 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white"
                                    oninput="document.getElementById('kondisiRange').value=this.value; updateCondisi(this.value)">
                            </div>
                            <div class="bg-rose-50 rounded-xl p-4">
                                <p class="text-xs font-semibold text-slate-500 mb-2">Panduan Penilaian</p>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between"><span class="text-red-600 font-medium">0 –
                                            50</span><span class="text-slate-600">Tidak Layak (Kondisi Buruk)</span></div>
                                    <div class="flex justify-between"><span class="text-yellow-600 font-medium">51 –
                                            89</span><span class="text-slate-600">Layak (Kondisi Normal)</span></div>
                                    <div class="flex justify-between"><span class="text-green-600 font-medium">90 –
                                            100</span><span class="text-slate-600">Sangat Layak (Kondisi Sangat Baik)</span>
                                    </div>
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
                <span
                    class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">4</span>
                Catatan Tambahan
            </h3>
            <textarea name="catatan" rows="3" placeholder="Catatan atau keterangan tambahan (opsional)..."
                class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white resize-none">{{ old('catatan') }}</textarea>
            <div class="flex gap-3 mt-5">
                <button type="submit" id="submitBtn"
                    class="btn-primary flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold text-sm">
                    <i class="fa-solid fa-calculator"></i> Proses Analisis Fuzzy 5C
                </button>
                <a href="{{ route('analis.dashboard') }}"
                    class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">Batal</a>
            </div>
        </div>
    </form>

    <script>
        // Load settings from backend
        const settings = @json($settings);
        const capSL = settings.capacity ? settings.capacity.batas_sangat_layak : 30;
        const capTL = settings.capacity ? settings.capacity.batas_tidak_layak : 70;
        const capitSL = settings.capital ? settings.capital.batas_sangat_layak : 200;
        const capitTL = settings.capital ? settings.capital.batas_tidak_layak : 0;
        const colSL = settings.collateral ? settings.collateral.batas_sangat_layak : 70;
        const colTL = settings.collateral ? settings.collateral.batas_tidak_layak : 110;

        function switchMode(mode) {
            document.getElementById('baruSection').classList.toggle('hidden', mode !== 'baru');
            document.getElementById('existingSection').classList.toggle('hidden', mode === 'baru');
        }

        // C1 Character (SLIK)
        function updateSlik(v) {
            v = parseInt(v);
            document.getElementById('slikRange').value = v;
            const el = document.getElementById('slikLabel');
            if (v === 1) {
                el.textContent = '✅ 1 — Excellent / Very Good / Good';
                el.className = 'text-sm font-bold text-green-600';
            } else if (v === 2) {
                el.textContent = '🟡 2 — Medium / Bad 1';
                el.className = 'text-sm font-bold text-yellow-600';
            } else {
                el.textContent = '⚠️ 3 — Bad 2 / Worst (Otomatis Tidak Layak)';
                el.className = 'text-sm font-bold text-red-600';
            }
        }

        // C2 Capacity: hitung cicilan & rasio
        function hitungRasio() {
            const pinjaman = parseFloat(document.getElementById('pinjaman').value) || 0;
            const penghasilan = parseFloat(document.getElementById('penghasilan').value) || 0;
            const jangka = parseInt(document.getElementById('jangkaWaktu').value) || 0;
            if (!pinjaman || !penghasilan || !jangka) return;

            const r = 0.12 / 12;
            const cicilan = pinjaman * (r * Math.pow(1 + r, jangka)) / (Math.pow(1 + r, jangka) - 1);
            const rasio = (cicilan / penghasilan) * 100;

            document.getElementById('estimasiCicilan').textContent = 'Rp ' + Math.round(cicilan).toLocaleString('id-ID');
            const rasioEl = document.getElementById('rasioLabel');
            rasioEl.textContent = rasio.toFixed(1) + '%';

            const capEl = document.getElementById('capacityLabel');

            // Hitung Skor Capacity (0-100) otomatis
            let score = 0;
            if (capSL < capTL) {
                if (rasio <= capSL) score = 100;
                else if (rasio >= capTL) score = 0;
                else score = 100 - ((rasio - capSL) / (capTL - capSL)) * 100;

                if (score <= 50) { rasioEl.className = 'text-lg font-bold text-red-600'; capEl.textContent = '⚠️ Tidak Layak'; capEl.className = 'text-sm font-semibold text-red-600'; }
                else if (score < 90) { rasioEl.className = 'text-lg font-bold text-yellow-600'; capEl.textContent = '🟡 Layak'; capEl.className = 'text-sm font-semibold text-yellow-600'; }
                else { rasioEl.className = 'text-lg font-bold text-green-600'; capEl.textContent = '✅ Sangat Layak'; capEl.className = 'text-sm font-semibold text-green-600'; }
            } else {
                if (rasio >= capSL) score = 100;
                else if (rasio <= capTL) score = 0;
                else score = ((rasio - capTL) / (capSL - capTL)) * 100;

                if (score <= 50) { rasioEl.className = 'text-lg font-bold text-red-600'; capEl.textContent = '⚠️ Tidak Layak'; capEl.className = 'text-sm font-semibold text-red-600'; }
                else if (score < 90) { rasioEl.className = 'text-lg font-bold text-yellow-600'; capEl.textContent = '🟡 Layak'; capEl.className = 'text-sm font-semibold text-yellow-600'; }
                else { rasioEl.className = 'text-lg font-bold text-green-600'; capEl.textContent = '✅ Sangat Layak'; capEl.className = 'text-sm font-semibold text-green-600'; }
            }

            document.getElementById('capacityInput').value = Math.round(score);
            document.getElementById('capacityRange').value = Math.round(score);
        }

        // C3 Capital: hitung aset bersih
        function hitungAsetBersih() {
            const aset = parseFloat(document.getElementById('totalAset').value) || 0;
            const hutang = parseFloat(document.getElementById('totalHutang').value) || 0;
            const pinjaman = parseFloat(document.getElementById('pinjaman').value) || 0;
            const asetBersih = aset - hutang;
            document.getElementById('asetBersih').value = asetBersih;

            // Hitung Skor Capital (0-100) otomatis
            let rasioAset = 0;
            if (pinjaman > 0) rasioAset = (asetBersih / pinjaman) * 100;

            let score = 0;
            if (capitSL > capitTL) {
                if (rasioAset >= capitSL) score = 100;
                else if (rasioAset <= capitTL) score = 0;
                else score = ((rasioAset - capitTL) / (capitSL - capitTL)) * 100;
            } else {
                if (rasioAset <= capitSL) score = 100;
                else if (rasioAset >= capitTL) score = 0;
                else score = 100 - ((rasioAset - capitSL) / (capitTL - capitSL)) * 100;
            }

            document.getElementById('capitalInput').value = Math.round(score);
            document.getElementById('capitalRange').value = Math.round(score);
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

            // Hitung Skor Collateral (0-100) otomatis
            let score = 0;
            if (colSL < colTL) {
                if (ltv <= colSL) score = 100;
                else if (ltv >= colTL) score = 0;
                else score = 100 - ((ltv - colSL) / (colTL - colSL)) * 100;

                if (score <= 50) { ltvEl.className = 'text-xl font-bold text-red-600'; collEl.textContent = '⚠️ Agunan Tidak Layak'; collEl.className = 'text-xs font-semibold text-red-600'; }
                else if (score < 90) { ltvEl.className = 'text-xl font-bold text-yellow-600'; collEl.textContent = '🟡 Agunan Layak'; collEl.className = 'text-xs font-semibold text-yellow-600'; }
                else { ltvEl.className = 'text-xl font-bold text-green-600'; collEl.textContent = '✅ Agunan Sangat Layak'; collEl.className = 'text-xs font-semibold text-green-600'; }
            } else {
                if (ltv >= colSL) score = 100;
                else if (ltv <= colTL) score = 0;
                else score = ((ltv - colTL) / (colSL - colTL)) * 100;

                if (score <= 50) { ltvEl.className = 'text-xl font-bold text-red-600'; collEl.textContent = '⚠️ Agunan Tidak Layak'; collEl.className = 'text-xs font-semibold text-red-600'; }
                else if (score < 90) { ltvEl.className = 'text-xl font-bold text-yellow-600'; collEl.textContent = '🟡 Agunan Layak'; collEl.className = 'text-xs font-semibold text-yellow-600'; }
                else { ltvEl.className = 'text-xl font-bold text-green-600'; collEl.textContent = '✅ Agunan Sangat Layak'; collEl.className = 'text-xs font-semibold text-green-600'; }
            }

            document.getElementById('collateralInput').value = Math.round(score);
            document.getElementById('collateralRange').value = Math.round(score);
        }

        // C5 Condition
        function updateCondisi(v) {
            v = parseFloat(v);
            document.getElementById('kondisiInput').value = v;
            document.getElementById('kondisiRange').value = v;
            const el = document.getElementById('condLabel');
            if (v <= 50) el.textContent = '⚠️ Tidak Layak', el.className = 'text-sm font-bold text-red-600';
            else if (v < 90) el.textContent = '🟡 Layak', el.className = 'text-sm font-bold text-yellow-600';
            else el.textContent = '✅ Sangat Layak', el.className = 'text-sm font-bold text-green-600';
        }

        // Init
        updateSlik(document.getElementById('slikRange').value);
        updateCondisi(document.getElementById('kondisiInput').value);
        switchMode('{{ old('mode', 'baru') }}');

        document.getElementById('analisisForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;
        });
    </script>
@endsection