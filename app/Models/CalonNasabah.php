<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonNasabah extends Model
{
    protected $table = 'calon_nasabah';
    protected $fillable = ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'telepon', 'pekerjaan', 'nama_usaha'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function hasilAnalisis()
    {
        return $this->hasMany(HasilAnalisis::class);
    }
}
