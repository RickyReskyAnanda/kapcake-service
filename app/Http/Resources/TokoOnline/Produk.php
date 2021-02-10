<?php

namespace App\Http\Resources\TokoOnline;

use Illuminate\Http\Resources\Json\JsonResource;

class Produk extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $variasi = $this->variasi;
        $jmlVariasi = count($variasi);

        $ket_harga = '';
        if($jmlVariasi > 1) $ket_harga  = $jmlVariasi.' Variasi';
        else $ket_harga = $variasi[0] ? number_format($variasi[0]->harga) : '';

        return [
                'id' => (int)$this->id_menu,
                'kategori_id' => $this->kategori_menu_id,
                'nama' => $this->nama_menu,
                'ket_harga' => $ket_harga,
                'thumb' => $this->thumbGambar->link ?? '',
                // 'keterangan' => $this->keterangan,
        ];
    }
}
