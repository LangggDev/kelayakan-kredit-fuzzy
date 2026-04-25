@extends('layouts.app')
@section('title', 'Analisis Ulang')
@section('page-title', 'Analisis Ulang — ' . $analisis->calonNasabah->nama)
@section('page-subtitle', 'Perbaiki parameter dan kirim ulang untuk persetujuan Kepala Cabang')

@section('content')

{{-- Info panel alasan penolakan --}}
<div class="rounded-2xl border-2 border-red-200 overflow-hidden">
    <div class="flex items-center gap-3 px-5 py-3" style="background:linear-gradient(135deg,#fef2f2,#fee2e2)">
        <i class="fa-solid fa-circle-xmark text-red-600 text-lg flex-shrink-0"></i>
        <div class="flex-1">
            <p class="font-bold text-red-800 text-sm">Analisis Sebelumnya Ditolak</p>
            <p class="text-xs text-red-600">
                Ditolak oleh <strong>{{ $analisis->approvedBy?->name ?? 'Kepala Cabang' }}</strong>
                &bull; {{ $analisis->approved_at?->format('d M Y, H:i') }}
            </p>
        </div>
        <a href="{{ route('analis.analisis.show', $analisis->id) }}"
            class="text-xs text-red-600 hover:underline font-medium flex-shrink-0">
            Lihat Analisis Lama →
        </a>
    </div>
    @if($analisis->catatan_approval)
    <div class="px-5 py-3 bg-white border-t border-red-100">
        <p class="text-xs font-semibold text-red-700 mb-1">
            <i class="fa-solid fa-comment-dots mr-1"></i> Alasan Penolakan dari Kepala Cabang:
        </p>
        <p class="text-sm text-red-800 font-medium bg-red-50 p-3 rounded-xl border border-red-100">
            "{{ $analisis->catatan_approval }}"
        </p>
    </div>
    @endif
</div>

{{-- Panduan perbaikan --}}
<div class="p-4 rounded-xl border flex items-start gap-3" style="background:#eef1f8; border-color:#d5ddef">
    <i class="fa-solid fa-lightbulb text-amber-500 flex-shrink-0 mt-0.5"></i>
    <div class="text-xs" style="color:#1a2e5a">
        <strong>Tips Perbaikan:</strong> Perhatikan alasan penolakan di atas, lalu sesuaikan nilai parameter yang bermasalah.
        Data nasabah sudah otomatis terisi dari analisis sebelumnya. Hanya parameter 5C yang perlu Anda perbaiki.
    </div>
</div>

@if($errors->any())
<div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
    <div class="font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Terdapat kesalahan:</div>
    <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('analis.analisis.storeRevisi', $analisis->id) }}" method="POST" id="revisiForm">
@csrf

{{-- Data Nasabah - Read Only --}}
<div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-4 text-sm flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold text-white" style="background:#1a2e5a">1</span>
        Data Calon Nasabah
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold" style="background:#eef1f8; color:#1a2e5a">
            <i class="fa-solid fa-lock mr-1"></i> Otomatis dari analisis sebelumnya
        </span>
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 rounded-xl p-4">
        <div>
            <p class="text-xs text-slate-400 font-medium mb-0.5">Nama Lengkap</p>
            <p class="font-semibold text-slate-800 text-sm">{{ $analisis->calonNasabah->nama }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 font-medium mb-0.5">NIK</p>
            <p class="font-semibold text-slate-800 text-sm font-mono">{{ $analisis->calonNasabah->nik }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 font-medium mb-0.5">Pekerjaan</p>
            <p class="font-semibold text-slate-800 text-sm">{{ $analisis->calonNasabah->pekerjaan ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 font-medium mb-0.5">Telepon</p>
            <p class="font-semibold text-slate-800 text-sm">{{ $analisis->calonNasabah->telepon ?? '—' }}</p>
        </div>
        <div class="md:col-span-2">
            <p class="text-xs text-slate-400 font-medium mb-0.5">Alamat</p>
            <p class="font-semibold text-slate-800 text-sm">{{ $analisis->calonNasabah->alamat ?? '—' }}</p>
        </div>
    </div>
</div>

{{-- Parameter 5C - Editable, pre-filled --}}
<div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-1 text-sm flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold text-white" style="background:#1a2e5a">2</span>
        Parameter Penilaian 5C
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
            <i class="fa-solid fa-pen mr-1"></i> Dapat diubah
        </span>
    </h3>
    <p class="text-xs text-slate-400 mb-5 ml-9">
        Nilai sudah terisi dari analisis sebelumnya. Ubah nilai yang perlu diperbaiki sesuai alasan penolakan.
    </p>

    <div class="space-y-4">

        {{-- C1: CHARACTER --}}
        <div class="border rounded-xl overflow-hidden" style="border-color:#bfdbfe">
            <div class="flex items-center gap-3 px-4 py-3 border-b" style="background:#eff6ff; border-color:#bfdbfe">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm text-white flex-shrink-0" style="background:#2563eb">C1</span>
                <div>
                    <p class="font-semibold text-blue-800 text-sm">Character — Skor Kredit (BI Checking)</p>
                    <p class="text-xs text-blue-500">0 = Buruk, 100 = Sempurna &bull; Nilai lama: <strong>{{ $analisis->skor_kredit }}</strong></p>
                </div>
                @if($analisis->skor_kredit <= 40)
                <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 flex-shrink-0">⚠ Rendah</span>
                @endif
            </div>
            <div class="p-4">
                <input type="number" name="skor_kredit" value="{{ old('skor_kredit', $analisis->skor_kredit) }}"
                    min="0" max="100" step="1" required
                    class="w-full max-w-xs px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono font-semibold" style="border-color:#bfdbfe">
                <p class="text-xs text-blue-500 mt-1">Buruk: 0–40 | Cukup: 41–60 | Baik: 61–100</p>
            </div>
        </div>

        {{-- C2: CAPACITY --}}
        <div class="border rounded-xl overflow-hidden" style="border-color:#bbf7d0">
            <div class="flex items-center gap-3 px-4 py-3 border-b" style="background:#f0fdf4; border-color:#bbf7d0">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm text-white flex-shrink-0" style="background:#16a34a">C2</span>
                <div>
                    <p class="font-semibold text-green-800 text-sm">Capacity — Kemampuan Membayar</p>
                    <p class="text-xs text-green-600">Rasio cicilan dihitung otomatis &bull; Nilai lama: Rp {{ number_format($analisis->penghasilan,0,',','.') }}</p>
                </div>
                @if($analisis->rasio_cicilan > 50)
                <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 flex-shrink-0">⚠ Beban Berat</span>
                @endif
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Penghasilan/Bln (Rp)</label>
                    <input type="number" name="penghasilan" id="penghasilan"
                        value="{{ old('penghasilan', $analisis->penghasilan) }}"
                        min="1" required oninput="hitungRasio()"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono" style="border-color:#bbf7d0">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah Pinjaman (Rp)</label>
                    <input type="number" name="jumlah_pinjaman" id="pinjaman"
                        value="{{ old('jumlah_pinjaman', $analisis->jumlah_pinjaman) }}"
                        min="1" required oninput="hitungRasio(); hitungLTV()"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono" style="border-color:#bbf7d0">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jangka Waktu (bulan)</label>
                    <input type="number" name="jangka_waktu" id="jangkaWaktu"
                        value="{{ old('jangka_waktu', $analisis->jangka_waktu) }}"
                        min="1" max="360" required oninput="hitungRasio()"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono" style="border-color:#bbf7d0">
                </div>
                <div class="md:col-span-3">
                    <div class="flex items-center gap-4 p-3 rounded-xl" style="background:#f0fdf4">
                        <div>
                            <p class="text-xs text-slate-500">Estimasi Cicilan/Bulan</p>
                            <p class="font-bold text-slate-800" id="estimasiCicilan">Rp —</p>
                        </div>
                        <div class="w-px h-8 bg-green-200"></div>
                        <div>
                            <p class="text-xs text-slate-500">Rasio Cicilan</p>
                            <p class="font-bold" id="rasioLabel">—</p>
                        </div>
                        <div class="w-px h-8 bg-green-200"></div>
                        <div>
                            <p class="text-xs text-slate-500">Sebelumnya</p>
                            <p class="text-xs font-semibold text-slate-500 line-through">{{ number_format($analisis->rasio_cicilan, 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- C3: CAPITAL --}}
        <div class="border rounded-xl overflow-hidden" style="border-color:#fde68a">
            <div class="flex items-center gap-3 px-4 py-3 border-b" style="background:#fffbeb; border-color:#fde68a">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm text-white flex-shrink-0" style="background:#d97706">C3</span>
                <div>
                    <p class="font-semibold text-amber-800 text-sm">Capital — Aset Bersih</p>
                    <p class="text-xs text-amber-600">Nilai lama: Rp {{ number_format($analisis->aset_bersih, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Total Aset (Rp)</label>
                    <input type="number" name="total_aset" id="totalAset" value="{{ old('total_aset') }}"
                        min="0" oninput="hitungAsetBersih()"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono" style="border-color:#fde68a">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Total Hutang (Rp)</label>
                    <input type="number" name="total_hutang" id="totalHutang" value="{{ old('total_hutang', 0) }}"
                        min="0" oninput="hitungAsetBersih()"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono" style="border-color:#fde68a">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Aset Bersih (Otomatis)</label>
                    <input type="number" name="aset_bersih" id="asetBersih"
                        value="{{ old('aset_bersih', $analisis->aset_bersih) }}"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm font-mono font-semibold" style="border-color:#fde68a; background:#fffbeb" readonly required>
                </div>
            </div>
        </div>

        {{-- C4: COLLATERAL --}}
        <div class="border rounded-xl overflow-hidden" style="border-color:#e9d5ff">
            <div class="flex items-center gap-3 px-4 py-3 border-b" style="background:#faf5ff; border-color:#e9d5ff">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm text-white flex-shrink-0" style="background:#9333ea">C4</span>
                <div>
                    <p class="font-semibold text-purple-800 text-sm">Collateral — Nilai Agunan</p>
                    <p class="text-xs text-purple-600">Nilai lama: Rp {{ number_format($analisis->nilai_agunan, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nilai Agunan (Rp)</label>
                    <input type="number" name="nilai_agunan" id="nilaiAgunan"
                        value="{{ old('nilai_agunan', $analisis->nilai_agunan) }}"
                        min="0" required oninput="hitungLTV()"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono" style="border-color:#e9d5ff">
                </div>
                <div class="p-3 rounded-xl flex items-center gap-3" style="background:#faf5ff">
                    <div>
                        <p class="text-xs text-slate-500">LTV Ratio</p>
                        <p class="font-bold text-purple-700" id="ltvLabel">—</p>
                    </div>
                    <div class="w-px h-8 bg-purple-200"></div>
                    <div>
                        <p class="text-xs text-slate-500">Sebelumnya</p>
                        <p class="text-xs font-semibold text-slate-400 line-through">{{ number_format($analisis->nilai_agunan > 0 ? ($analisis->jumlah_pinjaman / $analisis->nilai_agunan * 100) : 0, 1) }}%</p>
                    </div>
                    <div class="w-px h-8 bg-purple-200"></div>
                    <div>
                        <p class="text-xs text-slate-500">Status</p>
                        <p class="text-xs font-semibold" id="ltvStatus">—</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- C5: CONDITION --}}
        <div class="border rounded-xl overflow-hidden" style="border-color:#fecaca">
            <div class="flex items-center gap-3 px-4 py-3 border-b" style="background:#fff1f2; border-color:#fecaca">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm text-white flex-shrink-0" style="background:#e11d48">C5</span>
                <div>
                    <p class="font-semibold text-rose-800 text-sm">Condition — Kondisi Ekonomi</p>
                    <p class="text-xs text-rose-600">Nilai lama: <strong>{{ $analisis->kondisi_ekonomi }}</strong></p>
                </div>
            </div>
            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Skor (0–100)</label>
                    <input type="range" name="kondisi_ekonomi" id="kondisiRange"
                        min="0" max="100" value="{{ old('kondisi_ekonomi', $analisis->kondisi_ekonomi) }}"
                        class="w-full accent-rose-500" oninput="updateCondisi(this.value)">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>0 Buruk</span><span>50 Cukup</span><span>100 Baik</span>
                    </div>
                    <input type="number" name="kondisi_ekonomi" id="kondisiInput"
                        value="{{ old('kondisi_ekonomi', $analisis->kondisi_ekonomi) }}"
                        min="0" max="100" class="mt-2 w-full px-3.5 py-2.5 border rounded-xl text-sm bg-white font-mono" style="border-color:#fecaca"
                        oninput="document.getElementById('kondisiRange').value=this.value; updateCondisi(this.value)">
                </div>
                <div class="p-3 rounded-xl flex items-center" style="background:#fff1f2">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Status Kondisi</p>
                        <p class="font-bold text-rose-700 text-sm" id="condLabel">—</p>
                        <p class="text-xs text-slate-400 mt-1">Sebelumnya: <span class="line-through">{{ $analisis->kondisi_ekonomi }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Catatan --}}
<div class="card p-5">
    <h3 class="font-bold text-slate-800 mb-3 text-sm flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold text-white" style="background:#1a2e5a">3</span>
        Catatan Revisi
    </h3>
    <textarea name="catatan" rows="3"
        placeholder="Jelaskan perbaikan yang Anda lakukan dari analisis sebelumnya (opsional)..."
        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white resize-none">{{ old('catatan') }}</textarea>
</div>

{{-- Submit --}}
<div class="flex gap-3 flex-wrap mt-2">
    <button type="submit" id="submitBtn"
        class="btn-gold flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm"
        style="color:#1a2e5a">
        <i class="fa-solid fa-paper-plane"></i>
        Kirim Analisis Ulang
    </button>
    <a href="{{ route('analis.analisis.show', $analisis->id) }}"
        class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50">
        Batal
    </a>
</div>

</form>

<script>
function hitungRasio() {
    const p = parseFloat(document.getElementById('pinjaman').value) || 0;
    const g = parseFloat(document.getElementById('penghasilan').value) || 0;
    const j = parseInt(document.getElementById('jangkaWaktu').value) || 0;
    if (!p || !g || !j) return;
    const r = 0.12 / 12;
    const cicilan = p * (r * Math.pow(1+r,j)) / (Math.pow(1+r,j)-1);
    const rasio = (cicilan / g) * 100;
    document.getElementById('estimasiCicilan').textContent = 'Rp ' + Math.round(cicilan).toLocaleString('id-ID');
    const el = document.getElementById('rasioLabel');
    el.textContent = rasio.toFixed(1) + '%';
    el.className = 'font-bold ' + (rasio <= 30 ? 'text-green-600' : rasio <= 50 ? 'text-amber-600' : 'text-red-600');
}

function hitungAsetBersih() {
    const a = parseFloat(document.getElementById('totalAset').value) || 0;
    const h = parseFloat(document.getElementById('totalHutang').value) || 0;
    document.getElementById('asetBersih').value = a - h;
}

function hitungLTV() {
    const p = parseFloat(document.getElementById('pinjaman').value) || 0;
    const a = parseFloat(document.getElementById('nilaiAgunan').value) || 0;
    if (!a || !p) return;
    const ltv = (p / a) * 100;
    document.getElementById('ltvLabel').textContent = ltv.toFixed(1) + '%';
    const s = document.getElementById('ltvStatus');
    if (ltv < 70) { s.textContent = '✅ Kuat'; s.className = 'text-xs font-semibold text-green-600'; }
    else if (ltv <= 110) { s.textContent = '🟡 Cukup'; s.className = 'text-xs font-semibold text-amber-600'; }
    else { s.textContent = '⚠ Lemah'; s.className = 'text-xs font-semibold text-red-600'; }
}

function updateCondisi(v) {
    v = parseFloat(v);
    document.getElementById('kondisiInput').value = v;
    document.getElementById('kondisiRange').value = v;
    const el = document.getElementById('condLabel');
    if (v <= 40) el.textContent = '⚠️ Buruk — Ekonomi Lesu';
    else if (v <= 60) el.textContent = '🟡 Cukup — Kondisi Normal';
    else el.textContent = '✅ Baik — Ekonomi Tumbuh';
}

// Init
hitungRasio();
hitungLTV();
updateCondisi(document.getElementById('kondisiInput').value);

document.getElementById('revisiForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
    btn.disabled = true;
});
</script>
@endsection
