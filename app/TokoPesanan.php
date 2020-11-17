<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokoPesanan extends Model
{
    protected $table = "toko_pesanan";
    protected $primaryKey = "id_pesanan";
    /**
     * Fillable attribute.
     *
     * @var array
     */
   
    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->no_pesanan = 'order_'.date('YmdHis').'_'.$model->kode_toko.'_'.rand(1,999999);
            $model->status = 'pending';
            $model->bisnis_id = $model->outlet->bisnis_id ?? 0;
        });
    }

    public function bisnis(){
        return $this->belongsTo(Bisnis::class,'bisnis_id');
    }
    public function outlet(){
        return $this->belongsTo(Outlet::class,'outlet_id');
    }
    
    public function items(){
        return $this->hasMany(TokoPesananItem::class, 'pesanan_id');
    }
    // public function setTotal()
    // {
    //     $total = $this->entry()->sum('total');
    //     $this->attributes['total'] = $total;
    //     self::save();
    // }

    // /**
    //  * Set status to Pending
    //  *
    //  * @return void
    //  */
    // public function setPending()
    // {
    //     $this->attributes['status'] = 'pending';
    //     self::save();
    // }
 
    // /**
    //  * Set status to Success
    //  *
    //  * @return void
    //  */
    // public function setSuccess()
    // {
    //     $this->attributes['status'] = 'success';
    //     self::save();
    // }
 
    // /**
    //  * Set status to Failed
    //  *
    //  * @return void
    //  */
    // public function setFailed()
    // {
    //     $this->attributes['status'] = 'failed';
    //     self::save();
    // }
 
    // /**
    //  * Set status to Expired
    //  *
    //  * @return void
    //  */
    // public function setExpired()
    // {
    //     $this->attributes['status'] = 'expired';
    //     self::save();
    // }
}
