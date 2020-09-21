<?php

namespace App\Http\Resources\KasirPotrait;

use Illuminate\Http\Resources\Json\JsonResource;

class Diskon extends JsonResource
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
            'id_diskon' => $this->id_diskon,
			'nama_diskon' => $this->nama_diskon,
            'jumlah' => $this->jumlah,
			'tipe_diskon' => $this->tipe_diskon
        ];
    }
}


