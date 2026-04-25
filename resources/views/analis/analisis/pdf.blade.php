<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Kelayakan Kredit</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DejaVu Sans',Arial,sans-serif; font-size:10px; color:#1e293b; }
        
        .header { background:#4f46e5; color:white; padding:18px 24px; }
        .header-inner { display:table; width:100%; }
        .header-left  { display:table-cell; vertical-align:middle; }
        .header-right { display:table-cell; vertical-align:middle; text-align:right; }
        .header h1  { font-size:16px; font-weight:bold; margin-bottom:2px; }
        .header p   { font-size:9px; opacity:0.85; }
        .header .meta { font-size:9px; opacity:0.8; }

        .result-banner { padding:12px 24px; border-bottom:3px solid {{ $hasilAnalisis->keputusan==='Layak' ? '#16a34a' : '#dc2626' }}; background:{{ $hasilAnalisis->keputusan==='Layak' ? '#f0fdf4' : '#fef2f2' }}; }
        .result-banner .label { font-size:9px; color:#64748b; margin-bottom:2px; }
        .result-banner .keputusan { font-size:22px; font-weight:bold; color:{{ $hasilAnalisis->keputusan==='Layak' ? '#15803d' : '#dc2626' }}; }
        .result-banner .skor { font-size:10px; color:#64748b; margin-top:2px; }

        .score-table { width:100%; border-collapse:collapse; margin:10px 0; }
        .score-table td { width:20%; padding:8px 6px; text-align:center; border:1px solid #e2e8f0; }
        .score-box-code { font-size:11px; font-weight:bold; color:white; padding:3px 8px; border-radius:4px; display:inline-block; margin-bottom:3px; }
        .score-box-name { font-size:8px; color:#64748b; margin-bottom:4px; }
        .score-box-val  { font-size:18px; font-weight:bold; margin-bottom:2px; }
        .score-bar-bg { height:4px; background:#e2e8f0; border-radius:2px; margin:0 auto; width:60px; }
        .score-bar-fill { height:4px; border-radius:2px; }

        .section { padding:10px 24px; border-bottom:1px solid #f1f5f9; }
        .section h2 { font-size:10px; font-weight:bold; color:#4f46e5; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px; padding-bottom:4px; border-bottom:1px solid #e0e7ff; }

        .two-col { display:table; width:100%; }
        .col-left, .col-right { display:table-cell; width:50%; vertical-align:top; padding-right:12px; }
        .col-right { padding-right:0; padding-left:12px; }

        .info-row { display:table; width:100%; padding:3px 0; border-bottom:1px solid #f8fafc; }
        .info-label { display:table-cell; width:45%; color:#64748b; font-size:9px; }
        .info-value { display:table-cell; color:#1e293b; font-weight:600; font-size:9px; }

        table.data { width:100%; border-collapse:collapse; font-size:9px; margin-top:6px; }
        table.data th { background:#f8fafc; text-align:center; padding:5px 4px; font-weight:600; color:#64748b; text-transform:uppercase; font-size:8px; border:1px solid #e2e8f0; }
        table.data td { padding:4px; border:1px solid #e2e8f0; text-align:center; }
        table.data td.left { text-align:left; }
        table.data tfoot td { background:#eef2ff; font-weight:bold; }

        .badge { padding:2px 7px; border-radius:9999px; font-size:8px; font-weight:bold; }
        .badge-layak    { background:#dcfce7; color:#15803d; }
        .badge-tidaklayak { background:#fee2e2; color:#dc2626; }

        .formula { background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; padding:8px 12px; font-family:monospace; font-size:10px; margin-top:6px; }

        .footer { padding:8px 24px; text-align:center; font-size:8px; color:#94a3b8; border-top:1px solid #e2e8f0; margin-top:4px; }

        .page-break { page-break-before:always; }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-inner">
        <div class="header-left">
            <h1>Laporan Analisis Kelayakan Kredit</h1>
            <p>Metode Fuzzy Tsukamoto — Kriteria 5C (Character, Capacity, Capital, Collateral, Condition)</p>
        </div>
        <div class="header-right">
            <div class="meta">FuzzyKredit System</div>
            <div class="meta">Dicetak: {{ now()->format('d M Y, H:i') }}</div>
            <div class="meta">Oleh: {{ $hasilAnalisis->user->name }}</div>
        </div>
    </div>
</div>

{{-- RESULT BANNER --}}
<div class="result-banner">
    <div class="label">Keputusan Kelayakan Kredit</div>
    <div class="keputusan">{{ $hasilAnalisis->keputusan }}</div>
    <div class="skor">
        Skor Defuzzifikasi: <strong>{{ number_format($hasilAnalisis->nilai_defuzzifikasi, 4) }}</strong>
        &nbsp;|&nbsp; Kelayakan: <strong>{{ number_format($hasilAnalisis->persentase_kelayakan, 2) }}%</strong>
        &nbsp;|&nbsp; Threshold: 50 &nbsp;|&nbsp; Tanggal: {{ $hasilAnalisis->created_at->format('d M Y, H:i') }}
    </div>
</div>

{{-- 5C SCORE BOXES --}}
<div class="section">
    <h2>Skor Per Kriteria 5C</h2>
    <table class="score-table">
        <tr>
            @php
            $cItems = [
                ['C1','Character',  $hasilAnalisis->skor_character,  '#2563eb'],
                ['C2','Capacity',   $hasilAnalisis->skor_capacity,   '#16a34a'],
                ['C3','Capital',    $hasilAnalisis->skor_capital,    '#d97706'],
                ['C4','Collateral', $hasilAnalisis->skor_collateral, '#9333ea'],
                ['C5','Condition',  $hasilAnalisis->skor_condition,  '#e11d48'],
            ];
            @endphp
            @foreach($cItems as [$code, $name, $skor, $bgColor])
            @php $skor = $skor ?? 0; @endphp
            <td>
                <div><span class="score-box-code" style="background:{{ $bgColor }}">{{ $code }}</span></div>
                <div class="score-box-name">{{ $name }}</div>
                <div class="score-box-val" style="color:{{ $bgColor }}">{{ number_format($skor, 0) }}</div>
                <div class="score-box-name">/100</div>
                <div class="score-bar-bg">
                    <div class="score-bar-fill" style="width:{{ min($skor,100)*0.6 }}px; background:{{ $bgColor }}"></div>
                </div>
                <div class="score-box-name" style="margin-top:3px">{{ $skor >= 60 ? 'Baik' : ($skor >= 40 ? 'Cukup' : 'Kurang') }}</div>
            </td>
            @endforeach
        </tr>
    </table>
</div>

{{-- DATA NASABAH & INPUT --}}
<div class="section">
    <h2>Data Nasabah & Parameter Input</h2>
    <div class="two-col">
        <div class="col-left">
            <div style="font-size:9px;font-weight:bold;color:#64748b;margin-bottom:4px;text-transform:uppercase;">Data Calon Nasabah</div>
            <div class="info-row"><span class="info-label">Nama Lengkap</span><span class="info-value">{{ $hasilAnalisis->calonNasabah->nama }}</span></div>
            <div class="info-row"><span class="info-label">NIK</span><span class="info-value">{{ $hasilAnalisis->calonNasabah->nik }}</span></div>
            <div class="info-row"><span class="info-label">Pekerjaan</span><span class="info-value">{{ $hasilAnalisis->calonNasabah->pekerjaan ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Telepon</span><span class="info-value">{{ $hasilAnalisis->calonNasabah->telepon ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Alamat</span><span class="info-value">{{ $hasilAnalisis->calonNasabah->alamat ?? '-' }}</span></div>
        </div>
        <div class="col-right">
            <div style="font-size:9px;font-weight:bold;color:#64748b;margin-bottom:4px;text-transform:uppercase;">Parameter Input 5C</div>
            <div class="info-row"><span class="info-label">[C1] Skor Kredit</span><span class="info-value">{{ number_format($hasilAnalisis->skor_kredit, 0) }} / 100</span></div>
            <div class="info-row"><span class="info-label">[C2] Penghasilan/Bulan</span><span class="info-value">Rp {{ number_format($hasilAnalisis->penghasilan, 0, ',', '.') }}</span></div>
            <div class="info-row"><span class="info-label">[C2] Jumlah Pinjaman</span><span class="info-value">Rp {{ number_format($hasilAnalisis->jumlah_pinjaman, 0, ',', '.') }}</span></div>
            <div class="info-row"><span class="info-label">[C2] Jangka Waktu</span><span class="info-value">{{ $hasilAnalisis->jangka_waktu }} bulan</span></div>
            <div class="info-row"><span class="info-label">[C2] Rasio Cicilan (DSCR)</span><span class="info-value">{{ number_format($hasilAnalisis->rasio_cicilan, 2) }}%</span></div>
            <div class="info-row"><span class="info-label">[C3] Aset Bersih</span><span class="info-value">Rp {{ number_format($hasilAnalisis->aset_bersih, 0, ',', '.') }}</span></div>
            <div class="info-row"><span class="info-label">[C4] Nilai Agunan</span><span class="info-value">Rp {{ number_format($hasilAnalisis->nilai_agunan, 0, ',', '.') }}</span></div>
            <div class="info-row"><span class="info-label">[C5] Kondisi Ekonomi</span><span class="info-value">{{ number_format($hasilAnalisis->kondisi_ekonomi, 0) }} / 100</span></div>
        </div>
    </div>
</div>

{{-- FUZZIFIKASI --}}
@if(!empty($hasilAnalisis->nilai_fuzzifikasi))
<div class="section">
    <h2>Tahap 1: Fuzzifikasi</h2>
    @php
    $fuzzLabels = [
        'skor_kredit'     => ['Character (C1)',  ['buruk'=>'Buruk','cukup'=>'Cukup','baik'=>'Baik']],
        'rasio_cicilan'   => ['Capacity (C2)',   ['tinggi'=>'Tinggi','sedang'=>'Sedang','rendah'=>'Rendah']],
        'aset_bersih'     => ['Capital (C3)',    ['kecil'=>'Kecil','sedang'=>'Sedang','besar'=>'Besar']],
        'ltv_ratio'       => ['Collateral (C4)', ['rendah'=>'Rendah','sedang'=>'Sedang','tinggi'=>'Tinggi']],
        'kondisi_ekonomi' => ['Condition (C5)',  ['buruk'=>'Buruk','cukup'=>'Cukup','baik'=>'Baik']],
    ];
    @endphp
    <table class="data">
        <thead>
            <tr>
                <th class="left" style="width:20%">Parameter</th>
                <th style="width:16%">Himpunan 1</th><th style="width:12%">μ</th>
                <th style="width:16%">Himpunan 2</th><th style="width:12%">μ</th>
                <th style="width:16%">Himpunan 3</th><th style="width:12%">μ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fuzzLabels as $key => [$paramLabel, $hLabels])
            @if(isset($hasilAnalisis->nilai_fuzzifikasi[$key]))
            @php $vals = $hasilAnalisis->nilai_fuzzifikasi[$key]; $keys = array_keys($vals); $vals2 = array_values($vals); @endphp
            <tr>
                <td class="left"><strong>{{ $paramLabel }}</strong></td>
                <td>{{ $hLabels[$keys[0]] ?? $keys[0] }}</td><td>{{ number_format($vals2[0],4) }}</td>
                <td>{{ isset($keys[1]) ? ($hLabels[$keys[1]] ?? $keys[1]) : '—' }}</td><td>{{ isset($vals2[1]) ? number_format($vals2[1],4) : '—' }}</td>
                <td>{{ isset($keys[2]) ? ($hLabels[$keys[2]] ?? $keys[2]) : '—' }}</td><td>{{ isset($vals2[2]) ? number_format($vals2[2],4) : '—' }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- INFERENSI --}}
@if(!empty($hasilAnalisis->detail_rule))
<div class="section">
    <h2>Tahap 2 & 3: Inferensi Rule Fuzzy</h2>
    <table class="data">
        <thead>
            <tr>
                <th>R#</th>
                <th>μ C1</th><th>μ C2</th><th>μ C3</th><th>μ C4</th><th>μ C5</th>
                <th>α (min)</th><th>z</th><th>α×z</th><th>Output</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilAnalisis->detail_rule as $r)
            <tr>
                <td><strong>R{{ $r['nomor_rule'] }}</strong></td>
                <td>{{ number_format($r['mu_character'], 4) }}</td>
                <td>{{ number_format($r['mu_capacity'], 4) }}</td>
                <td>{{ number_format($r['mu_capital'], 4) }}</td>
                <td>{{ number_format($r['mu_collateral'], 4) }}</td>
                <td>{{ number_format($r['mu_condition'], 4) }}</td>
                <td><strong>{{ number_format($r['alpha'], 4) }}</strong></td>
                <td>{{ number_format($r['z'], 4) }}</td>
                <td>{{ number_format($r['alpha_z'], 4) }}</td>
                <td><span class="badge {{ $r['kelayakan']==='layak'?'badge-layak':'badge-tidaklayak' }}">{{ $r['kelayakan']==='layak'?'Layak':'Tdk Layak' }}</span></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right"><strong>Total:</strong></td>
                <td><strong>{{ number_format(array_sum(array_column($hasilAnalisis->detail_rule,'alpha')), 4) }}</strong></td>
                <td></td>
                <td><strong>{{ number_format(array_sum(array_column($hasilAnalisis->detail_rule,'alpha_z')), 4) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- DEFUZZIFIKASI --}}
<div class="section">
    <h2>Tahap 4: Defuzzifikasi (Weighted Average)</h2>
    @php
    $sumAZ = array_sum(array_column($hasilAnalisis->detail_rule,'alpha_z'));
    $sumA  = array_sum(array_column($hasilAnalisis->detail_rule,'alpha'));
    @endphp
    <div class="formula">
        z* = &Sigma;(&alpha;i &times; zi) / &Sigma;(&alpha;i) = {{ number_format($sumAZ,4) }} / {{ number_format($sumA,4) }} = <strong>{{ number_format($hasilAnalisis->nilai_defuzzifikasi,4) }}</strong>
        &nbsp;&nbsp;|&nbsp;&nbsp; Kelayakan = <strong>{{ number_format($hasilAnalisis->persentase_kelayakan,2) }}%</strong>
        &nbsp;&nbsp;|&nbsp;&nbsp; Threshold = 50
        &nbsp;&nbsp;|&nbsp;&nbsp; <strong style="color:{{ $hasilAnalisis->keputusan==='Layak'?'#15803d':'#dc2626' }}">{{ $hasilAnalisis->keputusan }}</strong>
    </div>
</div>
@endif

@if($hasilAnalisis->catatan)
<div class="section">
    <h2>Catatan</h2>
    <p style="font-size:10px;color:#475569;">{{ $hasilAnalisis->catatan }}</p>
</div>
@endif

<div class="footer">
    Dokumen ini digenerate otomatis oleh FuzzyKredit &bull; Sistem Penentu Kelayakan Kredit Metode Fuzzy Tsukamoto 5C &bull;
    Analis: {{ $hasilAnalisis->user->name }} &bull; {{ $hasilAnalisis->created_at->format('d M Y, H:i') }}
</div>

</body>
</html>
