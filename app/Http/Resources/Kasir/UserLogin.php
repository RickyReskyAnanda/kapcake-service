<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;
class UserLogin extends JsonResource
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
            'id'  => $this->id,
            'bisnis_id' => $this->bisnis_id,
            'nama' => $this->name,
            'email' => $this->email,
            'telpon' => $this->telpon,
            'alamat' => $this->alamat,
            'no_urut' => $this->no_urut,
            'is_super_admin' => $this->is_super_admin,
            'pin' => $this->pin,
            'outlet' => UserOutlet::collection($this->outlet),
        ];
        // 'nama' => $this->name,
        // 'email' => $this->email,
        // 'telpon' => $this->telpon,
        // 'alamat' => $this->alamat,
        // 'outlet_terpilih_id' => $this->outlet_terpilih_id,
        // 'jenis_item_terpilih' => $this->jenis_item_terpilih,
        // 'akses_outlet' => UsersOutlet::collection($this->outlet),
        // 'role' => $this->role,
    }

}
