<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    protected $table = 'matkuls';
    protected $primaryKey = 'id_matkul';
    protected $fillable = ['nama_matkul', 'jenis', 'sks', 'kapasitas_kelas'];
    public $timestamps = false;

    public function nilai()
    {
        return $this->hasMany(\App\Models\nilai::class, 'matakuliah_id', 'id_matkul');
    }
    
    public function jadwal()
    {
        return $this->hasMany(\App\Models\Jadwal::class, 'id_matkul', 'id_matkul');
    }
}
