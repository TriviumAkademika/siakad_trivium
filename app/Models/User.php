<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_mahasiswa',
        'id_dosen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    // Relasi dengan Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    // Accessor untuk mendapatkan nama display
    public function getDisplayNameAttribute()
    {
        if ($this->hasRole('mahasiswa') && $this->mahasiswa) {
            return $this->mahasiswa->nama;
        } elseif ($this->hasRole('dosen') && $this->dosen) {
            return $this->dosen->nama_dosen;
        } elseif ($this->hasRole('admin')) {
            return $this->name;
        }
        
        return $this->email; // fallback
    }

    // Accessor untuk mendapatkan role dari Spatie Permission
    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    // Method helper untuk mengecek apakah user sudah memiliki akun
    public function scopeWithoutRole($query, $role)
    {
        return $query->whereDoesntHave('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }
}