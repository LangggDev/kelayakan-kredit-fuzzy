<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonNasabah extends Model
{
    protected $table = 'calon_nasabah';
    protected $primaryKey = 'id_calon_nasabah';
    protected $fillable = ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'telepon', 'pekerjaan', 'nama_usaha'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function hasilAnalisis()
    {
        return $this->hasMany(HasilAnalisis::class, 'calon_nasabah_id');
    }

    public function getIdAttribute()
    {
        return $this->attributes['id_calon_nasabah'] ?? $this->getKey();
    }
}
