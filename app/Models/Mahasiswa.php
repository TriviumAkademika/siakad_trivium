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
}
