<?php

namespace App\Http\Resources;

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
                    'id' => $this->id_biaya_tambahan,
                    'nama' => $this->nama_biaya_tambahan,
                    'jumlah' => number_format($this->jumlah,2,",",".")
            ];
    }
}
