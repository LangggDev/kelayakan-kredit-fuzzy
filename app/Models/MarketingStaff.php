<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingStaff extends Model
{
    protected $table    = 'marketing_staff';
    protected $fillable = ['user_id', 'nip', 'area', 'telepon'];

    public function user() { return $this->belongsTo(User::class); }
}
