<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagihanEntry extends Model
{
    protected $table = 'tagihan_entry';
    protected $primaryKey = 'id_tagihan_entry';

    protected $guarded = [];

    public static function boot() {
        parent::boot();
         static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
        static::created(function ($model) {
            $model->total =  $model->paketLamaBerlangganan->harga ?? 0;
            $model->deskripsi =  ucwords(($model->paket->nama_paket ?? '-') . ' - ' . ($model->outlet->nama_outlet ??'-'));
            $model->save();
        });
    }

    public function parent(){
    	return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function bisnis(){
        return $this->belongsTo(Bisnis::class, 'bisnis_id');
    }

    public function outlet(){
    	return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public function paket(){
        return $this->belongsTo(Paket::class,'paket_id');
    }
    
    public function paketLamaBerlangganan(){
        return $this->belongsTo(PaketLamaBerlangganan::class,'paket_lama_berlangganan_id');
    }
}
