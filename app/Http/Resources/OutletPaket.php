<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutletPaket extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $kode = 88;
        if(isset($this->aktivasi))
            if(isset($this->aktivasi->kadaluarsa) && isset($this->aktivasi->tambahan_waktu)){
                $kadaluarsa = date('Y-m-d', strtotime($this->aktivasi->kadaluarsa));
                $tambahan_waktu = date('Y-m-d', strtotime($this->aktivasi->tambahan_waktu));

                if($kadaluarsa > date('Y-m-d', time()) ){
                    $kode = 77;
                }elseif($kadaluarsa <= date('Y-m-d', time()) && $tambahan_waktu > date('Y-m-d', time()) ){
                    $kode = 78;
                }elseif($tambahan_waktu < date('Y-m-d', time()) ){
                    $kode = 88;
                }
            }else{
                $kode = 88;
            }

            return [
                'id' => $this->id_outlet,
                'nama' => $this->nama_outlet ?? '',
                'kode' => $kode,
                'nama_paket' => $this->aktivasi && $kode != 88 ? $this->aktivasi->nama_paket : 'Free',
                'lama_berlangganan' => $this->aktivasi ? $this->aktivasi->lama_berlangganan : 'Selamanya',
                'jatuh_tempo' => $this->aktivasi ? date_indo($this->aktivasi->kadaluarsa) :'-',
                // free = 88
                // berbayar = 77
                // terkunci = 99
            ];
    }
}
