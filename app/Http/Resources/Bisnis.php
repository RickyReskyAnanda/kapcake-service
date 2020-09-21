<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Bisnis extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id_bisnis,
            'nama' => $this->nama_bisnis,
            'id_paket' => $this->paket_id,
            'deskripsi' => $this->deskripsi,
            'alamat' => $this->alamat,
            'kota' => $this->kota,
            'provinsi' => $this->provinsi,
            'kode_pos' => $this->kode_pos,
            'telpon' => $this->telpon,
            'email' => $this->email,
            'website' => $this->website,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'thumb_logo' => $this->thumbLogo->link ?? '',
            'kode' => $this->aktivasi ? 77:100,
        ];
    }
}
