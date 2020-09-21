<?php

namespace App\Http\Resources\Kasir;

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
            $urlGambar = null;
            if(isset($this->menu) == true)
                if(isset($this->menu->gambar) == true)
                    if(isset($this->menu->gambar[1]->link) == true)
                        $urlGambar = $this->menu->gambar[1]->link;

            return [
                'id_pemesanan_item' => $this->id_pemesanan_item,
                'unique_id' => $this->unique_id,
                'outlet_id' => $this->outlet_id,
                'user_id' => $this->user_id,
                'harga' => $this->harga,
                'jumlah' => $this->jumlah,
                'kategori_menu_id' => $this->kategori_menu_id,
                'nama_kategori_menu' => $this->nama_kategori_menu,
                'menu_id' => $this->menu_id,
                'nama_menu' => $this->nama_menu,
                'tipe_penjualan_id' => $this->tipe_penjualan_id,
                'nama_tipe_penjualan' => $this->nama_tipe_penjualan,
                'variasi_menu_id' => $this->variasi_menu_id,
                'nama_variasi_menu' => $this->nama_variasi_menu,
                'pemesanan_id' => $this->pemesanan_id,
                'total' => $this->total,
                'catatan' => $this->catatan,
                'gambar' => $urlGambar
            ];
    }
}

