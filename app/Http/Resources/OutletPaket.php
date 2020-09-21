<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutletPaket extends JsonResource
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
                'id' => $this->id_outlet,
                'nama' => $this->nama_outlet ?? '',
                'kode' => $this->aktivasi ? 77 : 88,
                'nama_paket' => $this->aktivasi ? $this->aktivasi->nama_paket : 'Free',
                'lama_berlangganan' => $this->aktivasi ? $this->aktivasi->lama_berlangganan : 'Selamanya',
                'jatuh_tempo' => $this->aktivasi ? date_indo($this->aktivasi->kadaluarsa) :'-',
                // free = 88
                // berbayar = 77
                // terkunci = 99
            ];
    }
}
