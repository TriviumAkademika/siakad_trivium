<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DosenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Cek role user login
        if ($request->user() && $request->user()->hasRole('admin')) {
            // Jika admin, tampilkan semua
            return [
                'id_dosen'    => $this->id_dosen,
                'nama_dosen'  => $this->nama_dosen,
                'nip'         => $this->nip,
                'alamat'      => $this->alamat,
                'no_hp'       => $this->no_hp,
                'status'      => $this->status,
            ];
        }

        // Jika mahasiswa atau dosen biasa, tampilkan yang diperbolehkan
        return [
            'nama_dosen' => $this->nama_dosen,
            'nip'        => $this->nip,
            'no_hp'      => $this->no_hp,
        ];
    }
}
