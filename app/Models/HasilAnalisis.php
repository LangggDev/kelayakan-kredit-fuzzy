<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAnalisis extends Model
{
    protected $table = 'hasil_analisis';

    protected $fillable = [
        'user_id', 'calon_nasabah_id',
        'skor_kredit', 'penghasilan', 'rasio_cicilan',
        'aset_bersih', 'nilai_agunan', 'jumlah_pinjaman', 'jangka_waktu',
        'kondisi_ekonomi',
        'nilai_fuzzifikasi', 'detail_rule',
        'nilai_defuzzifikasi', 'keputusan', 'persentase_kelayakan',
        'skor_character', 'skor_capacity', 'skor_capital', 'skor_collateral', 'skor_condition',
        'catatan',
        'status_approval', 'approved_by', 'approved_at', 'catatan_approval',
    ];

    protected $casts = [
        'nilai_fuzzifikasi'    => 'array',
        'detail_rule'          => 'array',
        'skor_kredit'          => 'float',
        'penghasilan'          => 'float',
        'rasio_cicilan'        => 'float',
        'aset_bersih'          => 'float',
        'nilai_agunan'         => 'float',
        'jumlah_pinjaman'      => 'float',
        'kondisi_ekonomi'      => 'float',
        'nilai_defuzzifikasi'  => 'float',
        'persentase_kelayakan' => 'float',
        'skor_character'       => 'float',
        'skor_capacity'        => 'float',
        'skor_capital'         => 'float',
        'skor_collateral'      => 'float',
        'skor_condition'       => 'float',
        'approved_at'          => 'datetime',
    ];

    public function user()         { return $this->belongsTo(User::class); }
    public function calonNasabah() { return $this->belongsTo(CalonNasabah::class, 'calon_nasabah_id'); }
    public function approvedBy()   { return $this->belongsTo(User::class, 'approved_by'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_approval) {
            'disetujui'   => 'Disetujui',
            'tidak_layak' => 'Tidak Layak',
            default       => 'Menunggu',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status_approval) {
            'disetujui'   => 'bg-green-100 text-green-700 border-green-200',
            'tidak_layak' => 'bg-slate-100 text-slate-500 border-slate-200',
            default       => 'bg-amber-100 text-amber-700 border-amber-200',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status_approval) {
            'disetujui'   => 'fa-circle-check',
            'tidak_layak' => 'fa-ban',
            default       => 'fa-clock',
        };
    }
}
