<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $table = 'parameter';
    protected $fillable = ['nama_parameter', 'kode', 'himpunan', 'tipe_fungsi', 'a', 'b', 'c', 'd', 'satuan', 'is_active'];

    protected $casts = [
        'a' => 'float',
        'b' => 'float',
        'c' => 'float',
        'd' => 'float',
        'is_active' => 'boolean',
    ];
}
