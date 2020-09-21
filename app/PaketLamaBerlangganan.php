<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaketLamaBerlangganan extends Model
{
    protected $table = 'paket_lama_berlangganan';
    protected $primaryKey = 'id_paket_lama_berlangganan';

    protected $guarded = [];

    public function parent(){
    	return $this->belongsTo(Paket::class, 'id_paket');
    }

    
}
