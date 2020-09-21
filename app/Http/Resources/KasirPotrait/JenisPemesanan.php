<?php

namespace App\Http\Resources\KasirPotrait;

use Illuminate\Http\Resources\Json\JsonResource;

class JenisPemesanan extends JsonResource
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
                'id_tipe_penjualan' => (int)$this->id_tipe_penjualan,
                'nama_tipe_penjualan' => $this->nama_tipe_penjualan,
        ];
    }
}
