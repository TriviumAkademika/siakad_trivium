<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frs extends Model
{
    protected $table = 'frs';
    protected $primaryKey = 'id_frs';
    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa', 'tahun_ajaran', 'semester', 'total_sks',
        'ips', 'ipk', 'tgl_pengisian', 'tgl_perubahan', 'tgl_drop'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function detailFrs()
    {
        return $this->hasMany(DetailFrs::class, 'id_frs');
    }
}
