<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuVariasi extends JsonResource
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
                'id' => $this->id_variasi_menu,
                'nama' => $this->nama_variasi_menu,
                'harga' => $this->harga,
                'sku' => $this->sku,
                'stok' => $this->stok,
                'stok_rendah' => $this->stok_rendah,
                'is_inventarisasi' => $this->is_inventarisasi,
        ];
    }
}
