<?php

namespace App\Http\Resources\KasirPotrait;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriMeja extends JsonResource
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
                'id_kategori_meja' => (int)$this->id_kategori_meja,
                'nama_kategori_meja' => $this->nama_kategori_meja,
        ];
    }
}
