<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nilai extends Model
{
    protected $table = 'nilais';
    protected $fillable = ['matakuliah_id', 'mahasiswa_id', 'nilai', 'jenis_nilai'];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function matkul()
    {
        return $this->belongsTo(\App\Models\Matkul::class, 'matakuliah_id', 'id_matkul');
    }

    // Removed matkuls() relationship as it's not needed and was causing issues
}