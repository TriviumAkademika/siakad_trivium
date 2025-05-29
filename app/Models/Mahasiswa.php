<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = [
        'id_kelas',
        'nama',
        'nrp',
        'semester',
        'gender',
        'alamat',
        'no_hp',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function frs()
    {
        return $this->hasMany(\App\Models\Frs::class, 'id_mahasiswa');
    }
    
    public function nilai()
    {
        return $this->hasMany(\App\Models\nilai::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    // Helper method untuk mendapatkan status dengan format yang lebih baik
    public function getStatusFormattedAttribute()
    {
        return ucfirst($this->status);
    }

    // Helper method untuk mendapatkan badge color berdasarkan status
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'aktif' => 'bg-green-100 text-green-800',
            'non-aktif' => 'bg-red-100 text-red-800',
            'cuti' => 'bg-yellow-100 text-yellow-800',
            'lulus' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}