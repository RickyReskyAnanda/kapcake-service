<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoriBarang extends JsonResource
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
                    'nama' => $this->nama_barang,
                    'stok_awal' => (int)$this->stok_awal,
                    'pesanan_pembelian' => $this->pesanan_pembelian,
                    'pemakaian' => (int)$this->pemakaian,
                    'transfer' => (int)$this->transfer,
                    'penyesuaian_stok' => (int)$this->penyesuaian_stok,
                    'stok_akhir' => (int)$this->stok_akhir
            ];
    }
}
