<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiskonShow extends JsonResource
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
                    'id' => $this->id_diskon,
                    'nama' => $this->nama_diskon,
                    'tipe_diskon' => $this->tipe_diskon,
                    'jumlah' => $this->jumlah,
            ];
    }
}
