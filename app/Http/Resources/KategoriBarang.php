<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriBarang extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $jumlah  =$this->jumlahBarang($request->outlet_id ?? 0);
        return [
                'id' => $this->id_kategori_barang,
                'nama' => $this->nama_kategori_barang,
                'outlet' => $this->outlet->nama_outlet ?? '',
                'is_paten' => $this->is_paten,
                'total_barang' => $jumlah > 0 ? $jumlah . ' Barang Ditandai' : '-',
        ];
    }
}
