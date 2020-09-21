<?php

namespace App\Http\Resources\KasirPotrait;

use Illuminate\Http\Resources\Json\JsonResource;

class Pajak extends JsonResource
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
            'id_pajak' => $this->id_pajak,
			'nama_pajak' => $this->nama_pajak,
			'jumlah' => $this->jumlah
        ];
    }
}


