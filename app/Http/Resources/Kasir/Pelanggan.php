<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;

class Pelanggan extends JsonResource
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
                'id_pelanggan' => (int)$this->id_pelanggan,
                'unique_id' => $this->unique_id ?? '',
                'nama_pelanggan' => $this->nama_pelanggan,
                'email' => $this->email,
                'no_hp' => $this->no_hp,
                'jk' => $this->jk,
                'is_uploaded' => 1,
        ];
    }
}
