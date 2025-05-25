<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $primaryKey = 'id_kelas';
    protected $fillable = ['id_dosen', 'tahun_masuk', 'prodi', 'paralel', 'status'];
    public $timestamps = false;

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_kelas', 'id_kelas');
    }
}