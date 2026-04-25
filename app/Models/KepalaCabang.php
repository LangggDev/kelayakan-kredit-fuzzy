<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaCabang extends Model
{
    protected $table    = 'kepala_cabang';
    protected $fillable = ['user_id', 'nip', 'cabang', 'telepon'];

    public function user() { return $this->belongsTo(User::class); }
}
