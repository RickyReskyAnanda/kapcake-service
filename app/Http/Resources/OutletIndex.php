<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutletIndex extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id_outlet,
            'nama' => $this->nama_outlet ?? '',
            'alamat' => $this->alamat ?? '',
            'kota' => $this->kota ?? '',
            'provinsi' => $this->provinsi ?? '',
            'kode_pos' => $this->kode_pos ?? '',
            'telpon' => $this->telpon ?? '',
            'nama_paket' => $this->aktivasi ? $this->aktivasi->nama_paket : 'Free',
            'is_paket_aktif' => $this->aktivasi ? 1 : 0,
        ];
    }
}
