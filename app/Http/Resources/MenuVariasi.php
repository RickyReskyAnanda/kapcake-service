<?php

namespace App\Http\Resources;

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
                'kategori_menu_id' => $this->kategori_menu_id,
                'tipe_penjualan' => MenuVariasiTipePenjualan::collection($this->tipePenjualan),
                'harga' => (int)$this->harga,
                'sku' => $this->sku,
                'stok' => (int)$this->stok,
                'stok_rendah' => (int)$this->stok_rendah,
                'is_inventarisasi' => (int)$this->is_inventarisasi,
        ];
    }
}
