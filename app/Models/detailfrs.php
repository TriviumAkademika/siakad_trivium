<?php
// app/Models/DetailFrs.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailFrs extends Model
{
    protected $table = 'detail_frs';
    protected $primaryKey = 'id_detail_frs';
    public $timestamps = false;

    protected $fillable = ['id_jadwal', 'id_frs', 'status'];

    public function frs()
    {
        return $this->belongsTo(Frs::class, 'id_frs');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }
}
