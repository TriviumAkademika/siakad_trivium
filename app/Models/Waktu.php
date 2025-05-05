<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waktu extends Model
{
    protected $primaryKey = 'id_waktu';
    protected $fillable = ['hari', 'jam_mulai', 'jam_selesai'];
    public $timestamps = false;
}
