<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $primaryKey = 'id_ruangan';
    protected $fillable = ['nama_ruangan', 'nama_gedung'];
    public $timestamps = false;
}
