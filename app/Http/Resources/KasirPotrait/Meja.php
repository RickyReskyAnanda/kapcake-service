<?php

namespace App\Http\Resources\KasirPotrait;

use Illuminate\Http\Resources\Json\JsonResource;

class Meja extends JsonResource
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
                'id_meja' => (int)$this->id_meja,
                'kategori_meja_id' => $this->kategori_meja_id,
                'nama_meja' => $this->nama_meja,
                'pax' => $this->pax,
                'bentuk' => $this->bentuk,
        ];
    }
}
