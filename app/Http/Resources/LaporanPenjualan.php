<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LaporanPenjualan extends JsonResource
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
                'nama_menu' => $this->nama_menu,
                'nama_variasi_menu' => $this->nama_variasi_menu,
                'outlet_id' => $this->outlet_id,
                'outlet' => $this->outlet,
                'total_penjualan_item' => $this->total_penjualan_item,
                'total_pengembalian_item' => $this->total_pengembalian_item,
                'total_penjualan_kotor' => $this->total_penjualan_kotor,
                'total_diskon' => round($this->total_diskon),
                'total_pengembalian_uang' => round($this->total_pengembalian_uang),
                'total_penjualan_bersih' => round($this->total_penjualan_bersih),
            ];
    }
}
