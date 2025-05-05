<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $primaryKey = 'id_kelas';
    protected $fillable = ['id_dosen', 'tahun_masuk', 'prodi', 'paralel'];
    public $timestamps = false;

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
}
