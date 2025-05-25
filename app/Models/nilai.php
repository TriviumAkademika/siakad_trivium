<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nilai extends Model
{
    protected $fillable = ['matakuliah_id', 'mahasiswa_id', 'nilai'];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function matkul()
    {
        return $this->belongsTo(\App\Models\Matkul::class, 'matakuliah_id', 'id_matkul');
    }

    public function matkuls()
    {
        return $this->belongsToMany(Matkul::class, 'nama_tabel_pivot', 'id_mahasiswa', 'id_matkul');
    }
}