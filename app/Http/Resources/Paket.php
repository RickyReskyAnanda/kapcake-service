<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Paket extends JsonResource
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
            'id' => $this->id_paket,
            'nama' => $this->nama_paket,
            'is_paket_utama' => $this->is_paket_utama,
            'harga' => $this->harga,
            'warna' => $this->warna,
            'satuan' => $this->satuan,
            'lama_berlangganan' => PaketLamaBerlangganan::collection($this->lamaBerlangganan),
            'entry' => PaketEntry::collection($this->entry)
        ];
    }
}
