<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $guarded = [];

    
    public function kategori(){
    	return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }

    public function satuan(){
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function setNamaBarangAttribute($value)
    {
        $this->attributes['nama_barang'] = ucfirst($value);
    }

    public function setKategoriBarangIdAttribute($value)
    {
        if((int)$value == 0)
            $this->attributes['kategori_barang_id'] = auth()->user()->bisnis->kategoriBarangPaten()->id_kategori_barang;
        else
            $this->attributes['kategori_barang_id'] = $value;
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::created(function ($model) {
            $user = auth()->user();
            if($model->is_inventarisasi == 1){
                $penyesuaianStok = auth()->user()->bisnis
                            ->penyesuaianStok()
                            ->create([
                                'catatan' => "Stok Awal",
                                'jumlah_item' =>  $model->stok,
                                'outlet_id' => $model->outlet_id,
                                'selisih_stok' => 0,
                                'tipe_item' =>  "barang"
                            ]);
            
                $penyesuaianStok
                    ->entry()
                    ->create([
                        'item_id' =>  $model->id_barang,
                        'selisih_stok' =>  0,
                        'stok_aktual' =>  $model->stok,
                        'stok_sistem' =>  $model->stok,
                        'tipe_item' =>  "barang"
                    ]);
            }
        });
    }
}
