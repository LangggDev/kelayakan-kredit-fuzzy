<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleFuzzy extends Model
{
    protected $table = 'rule_fuzzy';
    protected $primaryKey = 'id_rule_fuzzy';
    protected $fillable = [
        'nomor_rule', 'character', 'capacity', 'capital', 'collateral', 'condition',
        'kelayakan', 'output_a', 'output_b', 'output_tipe', 'is_active', 'deskripsi'
    ];
    protected $casts = [
        'output_a'  => 'float',
        'output_b'  => 'float',
        'is_active' => 'boolean',
    ];

    public function getIdAttribute()
    {
        return $this->attributes['id_rule_fuzzy'] ?? $this->getKey();
    }
}
