<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    protected $fillable = ['nama_dosen', 'nip', 'alamat', 'no_hp'];
    public $timestamps = false;

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_dosen');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id_dosen', 'id_dosen');
    }
}
