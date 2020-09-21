<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoriBahanDapur extends JsonResource
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
                    'nama' => $this->nama_bahan_dapur ?? '',
                    'variasi' => '',
                    'stok_awal' => $this->stok_awal,
                    'pesanan_pembelian' => $this->pesanan_pembelian,
                    'pemakaian' => $this->pemakaian,
                    'transfer' => $this->transfer,
                    'penyesuaian_stok' => $this->penyesuaian_stok,
                    'stok_akhir' => $this->stok_akhir
            ];
    }
}
