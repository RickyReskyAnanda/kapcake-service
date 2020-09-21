<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';
    protected $primaryKey = 'id_paket';
    protected $guarded = [];

    public function lamaBerlangganan(){
    	return $this->hasMany(PaketLamaBerlangganan::class,'paket_id')->where('is_aktif',1);
    }
    public function entry() {
    	return $this->hasMany(PaketEntry::class,'paket_id');
    }
}
