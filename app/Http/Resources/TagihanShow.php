<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagihanShow extends JsonResource
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
                    'id' => $this->id_tagihan,
                    'kode_invoice' => $this->kode_invoice,
                    'nama' => $this->name,
                    'email' => $this->email,
                    'entry' => TagihanEntry::collection($this->entry),
                    'status' => ucfirst($this->status),
                    'jatuh_tempo' => date_indo($this->jatuh_tempo).', '. timeFormat($this->jatuh_tempo),
                    'snap_token' => $this->snap_token,
                    'total' => $this->total,
                    'tanggal_jatuh_tempo' => $this->jatuh_tempo,
            ];
    }
}
