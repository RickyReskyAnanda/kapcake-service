<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersOutlet extends JsonResource
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
                'id' => $this->outlet->id_outlet,
                'nama' => $this->outlet->nama_outlet ?? '',
                'kode' => $this->outlet->aktivasi ? 77 : 88,
                'nama_paket' => $this->outlet->aktivasi ? $this->outlet->aktivasi->nama_paket : 'Free',
                'lama_berlangganan' => $this->outlet->aktivasi ? $this->outlet->aktivasi->lama_berlangganan : 'Selamanya',
                'jatuh_tempo' => $this->outlet->aktivasi ? date_indo($this->outlet->aktivasi->kadaluarsa) :'-',
                // free = 88
                // berbayar = 77
                // terkunci = 99
            ];
    }
}
