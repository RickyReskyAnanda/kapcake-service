<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Outlet extends JsonResource
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
            'id' => $this->id_outlet,
            'nama' => $this->nama_outlet ?? '',
            
        ];
    }
}