<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;

class Pesanan extends JsonResource
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
                    'id_pemesanan' => $this->id_pemesanan,
                    'bisnis_id' => $this->bisnis_id,
                    'outlet_id' => $this->outlet_id,
                    'pelayan_id' => $this->pelayan_id,

                    'tanggal_proses' => $this->tanggal_proses,
                    'tanggal_simpan' => $this->tanggal_simpan,
                    'waktu_proses' => $this->waktu_proses,
                    'waktu_simpan' => $this->waktu_simpan,

                    'kategori_meja_id' => $this->kategori_meja_id,
                    'nama_kategori_meja' => $this->nama_kategori_meja,

                    'meja_id' => $this->meja_id,
                    'nama_meja' => $this->nama_meja,

                    'kode_pemesanan' => $this->kode_pemesanan,
                    'no_pemesanan' => $this->no_pemesanan,

                    'pelanggan_id' => $this->pelanggan_id,
                    'nama_pelanggan' => $this->nama_pelanggan ? ucfirst($this->nama_pelanggan) :null,
                    'email_pelanggan' => $this->email_pelanggan,
                    'no_hp_pelanggan' => $this->no_hp_pelanggan,

                    'user_id' => $this->user_id,
                    'nama_user' => $this->nama_user,
                    'nama_pelayan' => $this->nama_pelayan,

                    'user_transfer_id' => $this->user_transfer_id,
                    'nama_user_transfer' => $this->nama_user_transfer,

                    'subtotal' => $this->subtotal,

                    'diskon_id' => $this->diskon_id,
                    'nama_diskon' => $this->nama_diskon,
                    'jumlah_diskon' => $this->jumlah_diskon,
                    'tipe_diskon' => $this->tipe_diskon,
                    'total_diskon' => $this->total_diskon,

                    'biaya_tambahan_id' => $this->biaya_tambahan_id,
                    'nama_biaya_tambahan' => $this->nama_biaya_tambahan,
                    'jumlah_biaya_tambahan' => $this->jumlah_biaya_tambahan,
                    'total_biaya_tambahan' => $this->total_biaya_tambahan,

                    'pajak_id' => $this->pajak_id,
                    'nama_pajak' => $this->nama_pajak,
                    'jumlah_pajak' => $this->jumlah_pajak,
                    'total_pajak' => $this->total_pajak,

                    'total' => $this->total,
                    'total_item' => (int)$this->total_item,

                    'catatan' => $this->catatan,

                    'item' => PesananItem::collection($this->item),
            ];
    }
}
