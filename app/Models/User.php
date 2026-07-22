<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function kreditAnalis()   { return $this->hasOne(KreditAnalis::class, 'user_id'); }
    public function kepalaCabang()   { return $this->hasOne(KepalaCabang::class, 'user_id'); }
    public function marketingStaff() { return $this->hasOne(MarketingStaff::class, 'user_id'); }
    public function hasilAnalisis()  { return $this->hasMany(HasilAnalisis::class, 'user_id'); }
    public function approvals()      { return $this->hasMany(HasilAnalisis::class, 'approved_by'); }

    public function isAdmin(): bool        { return $this->role === 'admin'; }
    public function isAnalis(): bool       { return $this->role === 'analis'; }
    public function isKepalaCabang(): bool { return $this->role === 'kepala_cabang'; }
    public function isMarketing(): bool    { return $this->role === 'marketing'; }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'         => 'Administrator',
            'analis'        => 'Kredit Analis',
            'kepala_cabang' => 'Kepala Cabang',
            'marketing'     => 'Marketing',
            default         => ucfirst($this->role),
        };
    }

    public function getAuthIdentifierName(): string
    {
        return 'id_user';
    }

    public function getIdAttribute()
    {
        return $this->attributes['id_user'] ?? $this->getKey();
    }
}
