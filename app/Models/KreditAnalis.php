<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KreditAnalis extends Model
{
    protected $table = 'kredit_analis';
    protected $fillable = ['user_id', 'nip', 'jabatan', 'telepon', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
