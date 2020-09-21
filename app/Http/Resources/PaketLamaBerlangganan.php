<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaketLamaBerlangganan extends JsonResource
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
            'id' => $this->id_paket_lama_berlangganan,
            'nama' => $this->keterangan,
            'harga' => $this->harga,
        ];
    }
}
