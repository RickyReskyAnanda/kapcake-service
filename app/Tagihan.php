<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = "tagihan";
    protected $primaryKey = "id_tagihan";
    /**
     * Fillable attribute.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'type',
    //     'amount',
    //     'note',
    // ];
    protected $guarded = [];

     public static function boot() {
        parent::boot();
        static::created(function ($model) {
            $model->kode_invoice =  'INV'.zeroFill($model->id_tagihan,5);
            $model->save();
        });
    }

    public function bisnis(){
        return $this->belongsTo(Bisnis::class,'bisnis_id');
    }
    
    public function entry(){
        return $this->hasMany(TagihanEntry::class, 'tagihan_id');
    }
    public function setTotal()
    {
        $total = $this->entry()->sum('total');
        $this->attributes['total'] = $total;
        self::save();
    }

    /**
     * Set status to Pending
     *
     * @return void
     */
    public function setPending()
    {
        $this->attributes['status'] = 'pending';
        self::save();
    }
 
    /**
     * Set status to Success
     *
     * @return void
     */
    public function setSuccess()
    {
        $this->attributes['status'] = 'success';
        self::save();
    }
 
    /**
     * Set status to Failed
     *
     * @return void
     */
    public function setFailed()
    {
        $this->attributes['status'] = 'failed';
        self::save();
    }
 
    /**
     * Set status to Expired
     *
     * @return void
     */
    public function setExpired()
    {
        $this->attributes['status'] = 'expired';
        self::save();
    }
}
