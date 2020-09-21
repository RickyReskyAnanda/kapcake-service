<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagihanEntry extends JsonResource
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
                'id' => $this->id_tagihan_entry,
                'outlet' => $this->outlet->nama_outlet ?? '-',
                'paket' => $this->paket->nama_paket ?? '-',
                'lama_berlangganan' => $this->paketLamaBerlangganan->keterangan ?? '-',
                'deskripsi' => $this->deskripsi,
                'total' => number_format($this->total),
            ];
    }
}
