<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aktivasi extends Model
{
    protected $table = 'aktivasi';
    protected $primaryKey = 'id_aktivasi';

    protected $guarded = [];

    public function bisnis(){
    	return $this->belongsTo(Bisnis::class, 'bisnis_id');
    }

    public function outlet(){
    	return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
