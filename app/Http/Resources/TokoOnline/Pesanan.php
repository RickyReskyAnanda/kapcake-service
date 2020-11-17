<?php

namespace App\Http\Resources\TokoOnline;

use Illuminate\Http\Resources\Json\JsonResource;

class Pesanan extends JsonResource
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
                'id' => (int)$this->id_pesanan,
                'shop_code' => $this->kode_toko,
                'order_number' => $this->no_pesanan,
                'items' => PesananItem::collection($this->items),
                'count_items' => count($this->jumlah_total),
                'subtotal' => $this->subtotal,
                'total' => $this->total,
                'status' => $this->status,
                'note' => $this->keterangan
        ];
    }
}
