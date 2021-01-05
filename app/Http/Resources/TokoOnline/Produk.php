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

        return [
                'id' => (int)$this->id_menu,
                'kategori' => $this->kategori->nama_kategori_menu ?? 'Lainnya',
                'nama' => $this->nama_menu,
                'varian' => $jmlVariasi > 1 ? $jmlVariasi .' Variasi':'',
                'mulai_dari' => $variasi[0] ? str_replace(",",".",number_format($variasi[0]->harga)) : '',
                'thumb' => $this->thumbGambar->link ?? '',
        ];
    }
}
