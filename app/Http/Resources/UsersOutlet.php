<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersOutlet extends JsonResource
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
        if(isset($this->outlet->aktivasi))
            if(isset($this->outlet->aktivasi->kadaluarsa) && isset($this->outlet->aktivasi->tambahan_waktu)){
                $kadaluarsa = date('Y-m-d', strtotime($this->outlet->aktivasi->kadaluarsa));
                $tambahan_waktu = date('Y-m-d', strtotime($this->outlet->aktivasi->tambahan_waktu));

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
            'id' => $this->outlet->id_outlet,
            'nama' => $this->outlet->nama_outlet ?? '',
            'kode' => $kode,
            'nama_paket' => isset($this->outlet->aktivasi) && $kode < 88? $this->outlet->aktivasi->nama_paket : 'Free',
            'lama_berlangganan' => $this->outlet->aktivasi ? $this->outlet->aktivasi->lama_berlangganan : 'Selamanya',
            'jatuh_tempo' => $this->outlet->aktivasi ? date_indo($this->outlet->aktivasi->kadaluarsa) :'-',
            'tanggal_kunci' => $this->outlet->aktivasi ? date_indo($this->outlet->aktivasi->tambahan_waktu) :'-'
            // free = 88
            // berbayar = 77
            // kadaluarsa = 78
            // terkunci = 99
        ];
    }
}
