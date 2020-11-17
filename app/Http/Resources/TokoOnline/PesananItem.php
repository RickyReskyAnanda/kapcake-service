<?php

namespace App\Http\Resources\TokoOnline;

use Illuminate\Http\Resources\Json\JsonResource;

class PesananItem extends JsonResource
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
                "_index" => $this->index_no,
                "menu_id" => (int)$this->menu_id,
                "menu_name" => $this->nama_menu,
                "variant_menu_id" => (int)$this->variasi_menu_id,
                "variant_menu_name" => $this->nama_variasi_menu,
                "qty" => (int)$this->jumlah,
                "price" => (int)$this->harga,
                "total" => (int)$this->total
        ];
    }
}
