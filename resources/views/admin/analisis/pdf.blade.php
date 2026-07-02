<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Kelayakan Kredit</title>
    <style>
        @page { margin: 20mm 18mm 25mm 18mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9.5px; color: #1e293b; line-height: 1.5; }

        /* ── Header / Letterhead ── */
        .header { border-bottom: 2px solid #334155; padding-bottom: 12px; margin-bottom: 14px; }
        .header-table { width: 100%; }
        .header-left { vertical-align: middle; }
        .header-right { vertical-align: middle; text-align: right; }
        .header h1 { font-size: 14px; font-weight: bold; color: #1e293b; letter-spacing: 0.3px; }
        .header .subtitle { font-size: 9px; color: #64748b; margin-top: 2px; }
        .header .doc-info { font-size: 8px; color: #64748b; line-height: 1.6; }
        .header .doc-id { font-size: 9px; font-weight: bold; color: #334155; }

        /* ── Result Box ── */
        .result-box { border: 1.5px solid {{ $hasilAnalisis->keputusan === 'Layak' ? '#16a34a' : '#dc2626' }}; padding: 10px 14px; margin-bottom: 14px; }
        .result-table { width: 100%; }
        .result-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .result-value { font-size: 18px; font-weight: bold; color: {{ $hasilAnalisis->keputusan === 'Layak' ? '#15803d' : '#dc2626' }}; }
        .result-detail { font-size: 9px; color: #475569; margin-top: 3px; }
        .result-right { text-align: right; vertical-align: middle; }
        .result-score { font-size: 20px; font-weight: bold; color: #334155; }
        .result-score-label { font-size: 8px; color: #64748b; }

        /* ── Section ── */
        .section { margin-bottom: 14px; }
        .section-title { font-size: 10px; font-weight: bold; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 4px; border-bottom: 1px solid #cbd5e1; margin-bottom: 8px; }
        .section-num { color: #64748b; font-weight: normal; margin-right: 4px; }

        /* ── Two Column Layout ── */
        .two-col { width: 100%; }
        .col-half { width: 49%; vertical-align: top; }
        .col-gap { width: 2%; }

        /* ── Info Table (Key-Value pairs) ── */
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 3px 0; font-size: 9px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .info-table .lbl { color: #64748b; width: 42%; }
        .info-table .val { color: #1e293b; font-weight: 600; }

        /* ── Score Summary Table ── */
        .score-summary { width: 100%; border-collapse: collapse; }
        .score-summary th { background: #f1f5f9; color: #475569; font-size: 8px; text-transform: uppercase; letter-spacing: 0.3px; padding: 5px 6px; text-align: center; border: 1px solid #e2e8f0; }
        .score-summary td { padding: 6px; text-align: center; border: 1px solid #e2e8f0; font-size: 9px; }
        .score-val { font-size: 14px; font-weight: bold; color: #1e293b; }
        .score-status { font-size: 7.5px; color: #64748b; margin-top: 2px; }

        /* ── Data Table ── */
        table.data { width: 100%; border-collapse: collapse; font-size: 8.5px; }
        table.data th { background: #f8fafc; text-align: center; padding: 5px 4px; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 7.5px; letter-spacing: 0.3px; border: 1px solid #e2e8f0; }
        table.data td { padding: 4px 3px; border: 1px solid #e2e8f0; text-align: center; }
        table.data td.left { text-align: left; }
        table.data tr.alt { background: #fafbfc; }
        table.data tfoot td { background: #f1f5f9; font-weight: bold; border-top: 2px solid #cbd5e1; }

        /* ── Badge ── */
        .badge { padding: 1px 6px; font-size: 7.5px; font-weight: bold; }
        .badge-l { background: #dcfce7; color: #15803d; }
        .badge-tl { background: #fee2e2; color: #dc2626; }

        /* ── Formula Box ── */
        .formula-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 12px; font-family: 'DejaVu Sans Mono', monospace; font-size: 9.5px; line-height: 1.8; }

        /* ── Footer ── */
        .footer { position: fixed; bottom: -15mm; left: 0; right: 0; text-align: center; font-size: 7.5px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }

        /* ── Signature ── */
        .sig-table { width: 100%; margin-top: 30px; }
        .sig-cell { width: 33%; text-align: center; vertical-align: top; padding: 0 10px; }
        .sig-line { border-top: 1px solid #475569; margin-top: 50px; padding-top: 4px; }
        .sig-name { font-weight: bold; font-size: 9px; color: #1e293b; }
        .sig-role { font-size: 8px; color: #64748b; }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

{{-- ═══════════════ FIXED FOOTER ═══════════════ --}}
<div class="footer">
    Dokumen ini dihasilkan secara otomatis oleh Sistem Analisis Kelayakan Kredit &mdash; Metode Fuzzy Tsukamoto 5C
</div>

{{-- ═══════════════ PAGE 1: HEADER & SUMMARY ═══════════════ --}}
<div class="header">
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="header-left">
                <h1>LAPORAN ANALISIS KELAYAKAN KREDIT</h1>
                <div class="subtitle">Metode Fuzzy Tsukamoto &mdash; Kriteria 5C (Character, Capacity, Capital, Collateral, Condition)</div>
            </td>
            <td class="header-right">
                <div class="doc-id">No. {{ str_pad($hasilAnalisis->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="doc-info">Tanggal Analisis: {{ $hasilAnalisis->created_at ? $hasilAnalisis->created_at->format('d M Y') : '-' }}</div>
                <div class="doc-info">Analis: {{ $hasilAnalisis->user->name }}</div>
                <div class="doc-info">Dicetak: {{ now()->format('d M Y, H:i') }}</div>
            </td>
        </tr>
    </table>
</div>

{{-- ── Keputusan ── --}}
<div class="result-box">
    <table class="result-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:65%">
                <div class="result-label">Keputusan Kelayakan Kredit</div>
                <div class="result-value">{{ strtoupper($hasilAnalisis->keputusan) }}</div>
                <div class="result-detail">
                    Skor Defuzzifikasi: <strong>{{ number_format($hasilAnalisis->nilai_defuzzifikasi, 4) }}</strong>
                    &nbsp;&middot;&nbsp; Persentase: <strong>{{ number_format($hasilAnalisis->persentase_kelayakan, 2) }}%</strong>
                    &nbsp;&middot;&nbsp; Threshold: > 70
                </div>
            </td>
            <td class="result-right">
                <div class="result-score">{{ number_format($hasilAnalisis->persentase_kelayakan, 2) }}%</div>
                <div class="result-score-label">SKOR KELAYAKAN</div>
            </td>
        </tr>
    </table>
</div>

{{-- ── Skor 5C ── --}}
<div class="section">
    <div class="section-title"><span class="section-num">I.</span> Ringkasan Skor Kriteria 5C</div>
    @php
    $cItems = [
        ['C1', 'Character',  $hasilAnalisis->skor_character  ?? 0],
        ['C2', 'Capacity',   $hasilAnalisis->skor_capacity   ?? 0],
        ['C3', 'Capital',    $hasilAnalisis->skor_capital     ?? 0],
        ['C4', 'Collateral', $hasilAnalisis->skor_collateral  ?? 0],
        ['C5', 'Condition',  $hasilAnalisis->skor_condition   ?? 0],
    ];
    @endphp
    <table class="score-summary">
        <thead>
            <tr>
                @foreach($cItems as [$code, $name, $skor])
                <th>{{ $code }} &mdash; {{ $name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($cItems as [$code, $name, $skor])
                <td>
                    <div class="score-val">{{ number_format($skor, 0) }}</div>
                    <div class="score-status">
                        @if($skor >= 70) Baik
                        @elseif($skor >= 40) Cukup
                        @else Kurang
                        @endif
                        / 100
                    </div>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

{{-- ── Data Nasabah & Parameter Input ── --}}
<div class="section">
    <div class="section-title"><span class="section-num">II.</span> Data Nasabah & Parameter Input</div>
    <table class="two-col" cellpadding="0" cellspacing="0">
        <tr>
            <td class="col-half">
                <table class="info-table">
                    <tr><td colspan="2" style="font-size:8px;font-weight:bold;color:#475569;text-transform:uppercase;padding-bottom:4px;border-bottom:1px solid #cbd5e1;">Data Calon Nasabah</td></tr>
                    <tr><td class="lbl">Nama Lengkap</td><td class="val">{{ $hasilAnalisis->calonNasabah->nama }}</td></tr>
                    <tr><td class="lbl">NIK</td><td class="val">{{ $hasilAnalisis->calonNasabah->nik }}</td></tr>
                    <tr><td class="lbl">Pekerjaan</td><td class="val">{{ $hasilAnalisis->calonNasabah->pekerjaan ?? '-' }}</td></tr>
                    <tr><td class="lbl">Telepon</td><td class="val">{{ $hasilAnalisis->calonNasabah->telepon ?? '-' }}</td></tr>
                    <tr><td class="lbl">Alamat</td><td class="val">{{ $hasilAnalisis->calonNasabah->alamat ?? '-' }}</td></tr>
                </table>
            </td>
            <td class="col-gap"></td>
            <td class="col-half">
                <table class="info-table">
                    <tr><td colspan="2" style="font-size:8px;font-weight:bold;color:#475569;text-transform:uppercase;padding-bottom:4px;border-bottom:1px solid #cbd5e1;">Parameter Keuangan</td></tr>
                    <tr><td class="lbl">Penghasilan / Bulan</td><td class="val">Rp {{ number_format($hasilAnalisis->penghasilan, 0, ',', '.') }}</td></tr>
                    <tr><td class="lbl">Jumlah Pinjaman</td><td class="val">Rp {{ number_format($hasilAnalisis->jumlah_pinjaman, 0, ',', '.') }}</td></tr>
                    <tr><td class="lbl">Jangka Waktu</td><td class="val">{{ $hasilAnalisis->jangka_waktu }} bulan</td></tr>
                    <tr><td class="lbl">Rasio Cicilan (DSCR)</td><td class="val">{{ number_format($hasilAnalisis->rasio_cicilan, 2) }}%</td></tr>
                    <tr><td class="lbl">Aset Bersih</td><td class="val">Rp {{ number_format($hasilAnalisis->aset_bersih, 0, ',', '.') }}</td></tr>
                    <tr><td class="lbl">Nilai Agunan</td><td class="val">Rp {{ number_format($hasilAnalisis->nilai_agunan, 0, ',', '.') }}</td></tr>
                    <tr><td class="lbl">Skor Kredit (SLIK)</td><td class="val">{{ number_format($hasilAnalisis->skor_kredit, 0) }} / 100</td></tr>
                    <tr><td class="lbl">Kondisi Ekonomi</td><td class="val">{{ number_format($hasilAnalisis->kondisi_ekonomi, 0) }} / 100</td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>

{{-- ── Fuzzifikasi ── --}}
@if(!empty($hasilAnalisis->nilai_fuzzifikasi))
<div class="section">
    <div class="section-title"><span class="section-num">III.</span> Tahap 1 &mdash; Fuzzifikasi</div>
    @php
    $fuzzLabels = [
        'skor_kredit'     => ['Character (C1)',  ['buruk'=>'Slik 3','cukup'=>'Slik 2','baik'=>'Slik 1']],
        'rasio_cicilan'   => ['Capacity (C2)',   ['tinggi'=>'Sangat Layak','sedang'=>'Layak','rendah'=>'Tidak Layak']],
        'aset_bersih'     => ['Capital (C3)',    ['kecil'=>'Tidak Layak','sedang'=>'Layak','besar'=>'Sangat Layak']],
        'ltv_ratio'       => ['Collateral (C4)', ['rendah'=>'Tidak Layak','sedang'=>'Layak','tinggi'=>'Sangat Layak']],
        'kondisi_ekonomi' => ['Condition (C5)',  ['buruk'=>'Tidak Layak','cukup'=>'Layak','baik'=>'Sangat Layak']],
    ];
    @endphp
    <table class="data">
        <thead>
            <tr>
                <th class="left" style="width:18%">Parameter</th>
                <th style="width:14%">Himpunan 1</th><th style="width:10%">&mu;</th>
                <th style="width:14%">Himpunan 2</th><th style="width:10%">&mu;</th>
                <th style="width:14%">Himpunan 3</th><th style="width:10%">&mu;</th>
            </tr>
        </thead>
        <tbody>
            @php $fIdx = 0; @endphp
            @foreach($fuzzLabels as $key => [$paramLabel, $hLabels])
            @if(isset($hasilAnalisis->nilai_fuzzifikasi[$key]))
            @php
                $vals = $hasilAnalisis->nilai_fuzzifikasi[$key];
                $keys = array_keys($vals);
                $vals2 = array_values($vals);
                $fIdx++;
            @endphp
            <tr class="{{ $fIdx % 2 === 0 ? 'alt' : '' }}">
                <td class="left"><strong>{{ $paramLabel }}</strong></td>
                <td>{{ $hLabels[$keys[0]] ?? $keys[0] }}</td><td>{{ number_format($vals2[0], 4) }}</td>
                <td>{{ isset($keys[1]) ? ($hLabels[$keys[1]] ?? $keys[1]) : '—' }}</td><td>{{ isset($vals2[1]) ? number_format($vals2[1], 4) : '—' }}</td>
                <td>{{ isset($keys[2]) ? ($hLabels[$keys[2]] ?? $keys[2]) : '—' }}</td><td>{{ isset($vals2[2]) ? number_format($vals2[2], 4) : '—' }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── Inferensi ── --}}
@if(!empty($hasilAnalisis->detail_rule))
<div class="section">
    <div class="section-title"><span class="section-num">IV.</span> Tahap 2 & 3 &mdash; Inferensi Rule Fuzzy</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width:5%">R#</th>
                <th>&mu; C1</th><th>&mu; C2</th><th>&mu; C3</th><th>&mu; C4</th><th>&mu; C5</th>
                <th>&alpha; (min)</th><th>z</th><th>&alpha;&times;z</th><th>Output</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilAnalisis->detail_rule as $i => $r)
            <tr class="{{ $i % 2 === 1 ? 'alt' : '' }}">
                <td><strong>R{{ $r['nomor_rule'] }}</strong></td>
                <td>{{ number_format($r['mu_character'], 4) }}</td>
                <td>{{ number_format($r['mu_capacity'], 4) }}</td>
                <td>{{ number_format($r['mu_capital'], 4) }}</td>
                <td>{{ number_format($r['mu_collateral'], 4) }}</td>
                <td>{{ number_format($r['mu_condition'], 4) }}</td>
                <td><strong>{{ number_format($r['alpha'], 4) }}</strong></td>
                <td>{{ number_format($r['z'], 4) }}</td>
                <td>{{ number_format($r['alpha_z'], 4) }}</td>
                <td><span class="badge {{ $r['kelayakan'] === 'layak' ? 'badge-l' : 'badge-tl' }}">{{ $r['kelayakan'] === 'layak' ? 'Layak' : 'Tidak Layak' }}</span></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right"><strong>Total</strong></td>
                <td><strong>{{ number_format(array_sum(array_column($hasilAnalisis->detail_rule, 'alpha')), 4) }}</strong></td>
                <td></td>
                <td><strong>{{ number_format(array_sum(array_column($hasilAnalisis->detail_rule, 'alpha_z')), 4) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- ── Defuzzifikasi ── --}}
<div class="section">
    <div class="section-title"><span class="section-num">V.</span> Tahap 4 &mdash; Defuzzifikasi (Weighted Average)</div>
    @php
    $sumAZ = array_sum(array_column($hasilAnalisis->detail_rule, 'alpha_z'));
    $sumA  = array_sum(array_column($hasilAnalisis->detail_rule, 'alpha'));
    @endphp
    <div class="formula-box">
        <div>z* = &Sigma;(&alpha;i &times; zi) / &Sigma;(&alpha;i)</div>
        <div>z* = {{ number_format($sumAZ, 4) }} / {{ number_format($sumA, 4) }} = <strong>{{ number_format($hasilAnalisis->nilai_defuzzifikasi, 4) }}</strong></div>
        <div style="margin-top:4px;">
            Persentase Kelayakan = <strong>{{ number_format($hasilAnalisis->persentase_kelayakan, 2) }}%</strong>
            &nbsp;&nbsp;|&nbsp;&nbsp; Threshold: > 70
            &nbsp;&nbsp;|&nbsp;&nbsp; Keputusan: <strong style="color:{{ $hasilAnalisis->keputusan === 'Layak' ? '#15803d' : '#dc2626' }}">{{ strtoupper($hasilAnalisis->keputusan) }}</strong>
        </div>
    </div>
</div>
@endif

{{-- ── Catatan ── --}}
@if($hasilAnalisis->catatan)
<div class="section">
    <div class="section-title"><span class="section-num">VI.</span> Catatan Analis</div>
    <p style="font-size:9.5px;color:#475569;">{{ $hasilAnalisis->catatan }}</p>
</div>
@endif

{{-- ── Tanda Tangan ── --}}
<table class="sig-table" cellpadding="0" cellspacing="0">
    <tr>
        <td class="sig-cell">
            <div style="font-size:8px;color:#64748b;">Dibuat oleh,</div>
            <div class="sig-line">
                <div class="sig-name">{{ $hasilAnalisis->user->name }}</div>
                <div class="sig-role">Analis Kredit</div>
            </div>
        </td>
        <td class="sig-cell">
            &nbsp;
        </td>
        <td class="sig-cell">
            <div style="font-size:8px;color:#64748b;">Mengetahui,</div>
            <div class="sig-line">
                <div class="sig-name">{{ $hasilAnalisis->approvedBy->name ?? '________________________' }}</div>
                <div class="sig-role">Kepala Cabang</div>
            </div>
        </td>
    </tr>
</table>

</body>
</html>
