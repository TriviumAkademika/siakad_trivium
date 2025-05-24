<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nilai extends Model
{
    protected $fillable = ['id_jadwal', 'id_mahasiswa', 'nilai'];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function jadwal()
    {
        return $this->belongsTo(\App\Models\Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function matkul()
    {
        return view('nilai.nilai-dosen', ['matkuls' => $matkuls]);
    }
}
