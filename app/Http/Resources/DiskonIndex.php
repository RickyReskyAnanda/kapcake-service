<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiskonIndex extends JsonResource
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
                    'jumlah' => $this->tipe_diskon == 'persen' ? number_format($this->jumlah,2,",",".").'%' : 'Rp '.number_format($this->jumlah)
            ];
    }
}
