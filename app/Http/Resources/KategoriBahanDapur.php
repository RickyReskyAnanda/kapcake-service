<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriBahanDapur extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $jumlah = $this->bahanDapur->count();
        return [
                'id' => $this->id_kategori_bahan_dapur,
                'nama' => $this->nama_kategori_bahan_dapur,
                'outlet' => $this->outlet->nama_outlet ?? '',
                'is_paten' => $this->is_paten,
                'total_bahan_dapur' => $jumlah > 0 ? $jumlah . ' Bahan Dapur Ditandai' : '-',
        ];
    }
}
