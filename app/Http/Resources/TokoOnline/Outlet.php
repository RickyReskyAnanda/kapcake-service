<?php

namespace App\Http\Resources\TokoOnline;

use Illuminate\Http\Resources\Json\JsonResource;

class Outlet extends JsonResource
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
                'nama' => $this->nama_outlet,
                'kode_toko' => $this->kode_toko_online,
                'alamat' => $this->alamat,
                'kota' => $this->kota,
                'provinsi' => $this->provinsi,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'nama_bisnis' => $this->bisnis->nama_bisnis ?? '',
        ];
    }
}
