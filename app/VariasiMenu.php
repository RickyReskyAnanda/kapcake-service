<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\VariasiMenuCreated;

class VariasiMenu extends Model
{
    protected $table = 'variasi_menu';
    protected $primaryKey = 'id_variasi_menu';

    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => VariasiMenuCreated::class
    ];

    public function menu(){
        return $this->belongsTo(Menu::class,'menu_id');
    }

    public function tipePenjualan(){
        return $this->hasMany(VariasiMenuTipePenjualan::class, 'variasi_menu_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
            if($model->is_inventarisasi == 0){
                $model->stok = 0;
                $model->stok_rendah = 0;
            }
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
                                'tipe_item' =>  "menu"
                            ]);
            
                $penyesuaianStok
                    ->entry()
                    ->create([
                        'item_id' =>  $model->id_variasi_menu,
                        'selisih_stok' =>  0,
                        'stok_aktual' =>  $model->stok,
                        'stok_sistem' =>  $model->stok,
                        'tipe_item' =>  "menu"
                    ]);
            }
        });
        static::deleting(function ($model) {
            $model->tipePenjualan()->delete();
        });
    }
}
