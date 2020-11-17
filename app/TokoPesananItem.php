<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokoPesananItem extends Model
{
    protected $table = 'toko_pesanan_item';
    protected $primaryKey = 'id_pesanan_item';

    protected $guarded = [];

    public static function boot() {
        parent::boot();
         static::creating(function ($model) {
            $model->index_no = '_'.str_random(20);
            $model->bisnis_id = $model->parent->bisnis_id;
            $model->outlet_id = $model->parent->outlet_id;
        
        });
        // static::created(function ($model) {
        //     $model->save();
        // });
    }

    public function parent(){
    	return $this->belongsTo(\App\TokoPesanan::class, 'pesanan_id');
    }

    public function bisnis(){
        return $this->belongsTo(Bisnis::class, 'bisnis_id');
    }

    public function outlet(){
    	return $this->belongsTo(Outlet::class,'outlet_id');
    }

    // public function paket(){
    //     return $this->belongsTo(Paket::class,'paket_id');
    // }
    
    // public function paketLamaBerlangganan(){
    //     return $this->belongsTo(PaketLamaBerlangganan::class,'paket_lama_berlangganan_id');
    // }
}
