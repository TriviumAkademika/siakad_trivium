<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    protected $fillable = ['nama_dosen', 'nip', 'alamat', 'no_hp', 'status'];
    public $timestamps = false;

    // Status constants untuk kemudahan penggunaan
    const STATUS_AKTIF = 'AKTIF';
    const STATUS_CUTI = 'CUTI';
    const STATUS_PENSIUN = 'PENSIUN';
    const STATUS_TIDAK_AKTIF = 'TIDAK AKTIF';

    // Method untuk mendapatkan semua status yang tersedia
    public static function getStatusOptions()
    {
        return [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_CUTI => 'Cuti',
            self::STATUS_PENSIUN => 'Pensiun',
            self::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
        ];
    }

    // Scope untuk filter berdasarkan status
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    public function scopeCuti($query)
    {
        return $query->where('status', self::STATUS_CUTI);
    }

    public function scopePensiun($query)
    {
        return $query->where('status', self::STATUS_PENSIUN);
    }

    public function scopeTidakAktif($query)
    {
        return $query->where('status', self::STATUS_TIDAK_AKTIF);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_dosen');
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'id_dosen', 'id_dosen');
    }
}