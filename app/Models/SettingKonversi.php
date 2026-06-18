<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingKonversi extends Model
{
    protected $fillable = [
        'kriteria',
        'batas_sangat_layak',
        'batas_tidak_layak',
    ];
}
