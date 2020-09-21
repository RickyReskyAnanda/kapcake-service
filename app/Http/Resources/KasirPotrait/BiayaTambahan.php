<?php

namespace App\Http\Resources\KasirPotrait;

use Illuminate\Http\Resources\Json\JsonResource;

class BiayaTambahan extends JsonResource
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
            'id_biaya_tambahan' => $this->id_biaya_tambahan,
			'nama_biaya_tambahan' => $this->nama_biaya_tambahan,
			'jumlah' => $this->jumlah
        ];
    }
}


