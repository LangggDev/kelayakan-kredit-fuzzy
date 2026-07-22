<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingKonversi extends Model
{
    protected $table = 'setting_konversis';
    protected $primaryKey = 'id_setting_konversi';
    protected $fillable = [
        'kriteria',
        'batas_sangat_layak',
        'batas_tidak_layak',
    ];

    public function getIdAttribute()
    {
        return $this->attributes['id_setting_konversi'] ?? $this->getKey();
    }
}
