<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KreditAnalis extends Model
{
    protected $table = 'kredit_analis';
    protected $primaryKey = 'id_kredit_analis';
    protected $fillable = ['user_id', 'nip', 'jabatan', 'telepon', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function getIdAttribute()
    {
        return $this->attributes['id_kredit_analis'] ?? $this->getKey();
    }
}
