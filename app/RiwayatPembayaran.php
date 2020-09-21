<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaran extends Model
{
    protected $table = 'riwayat_pembayaran';
    protected $primaryKey = 'id_riwayat_pembayaran';

    protected $guarded = [];

    public function bisnis(){
    	return $this->belongsTo(Bisnis::class,'bisnis_id');
    }
}
