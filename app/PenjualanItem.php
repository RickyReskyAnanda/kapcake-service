<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenjualanItem extends Model
{
     protected $table = 'penjualan_item';
    protected $primaryKey = 'id_penjualan_item';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->user_id = $user->id;
            $model->bisnis_id = $user->bisnis_id;
        });
        static::created(function ($model) {
            if($model->variasiMenu && $model->variasiMenu->is_inventarisasi == 1){
                $stok = (float)$model->variasiMenu->stok - (float)$model->jumlah;
                $model->variasiMenu()->update([
                    'stok' => $stok
                ]);

                auth()->user()->bisnis->inventoriMenu()
                ->create([
                    'outlet_id' => $model->outlet_id,
                    'menu_id' => $model->menu_id,
                    'Variasi_menu_id' => $model->variasi_menu_id,
                    'kategori_menu_id' => 0,
                    'tanggal' => date('Y-m-d'),
                    'stok_awal' => 0,
                    'penjualan' => $model->jumlah,
                    'transfer' => 0,
                    'penyesuaian_Stok' => 0,
                    'stok_akhir' => $stok
                ]);
            }
        });

    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function penjualan(){
        return $this->belongsTo(Penjualan::class,'penjualan_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function menu(){
        return $this->belongsTo(Menu::class,'menu_id');
    }

    public function kategoriMenu(){
        return $this->belongsTo(KategoriMenu::class,'kategori_menu_id');
    }

    public function variasiMenu(){
        return $this->belongsTo(VariasiMenu::class,'variasi_menu_id');
    }

    public function tipePenjualan(){
        return $this->belongsTo(TipePenjualan::class,'tipe_penjualan_id');
    }

    public function bisnis(){
        return $this->belongsTo(Bisnis::class,'bisnis_id');
    }
}
