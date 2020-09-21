<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyesuaianStokEntry extends Model
{
    protected $table = 'penyesuaian_stok_entry';
    protected $primaryKey = 'id_penyesuaian_stok_entry';

    protected $guarded = [];

    
    public function item(){
        if($this->tipe_item == 'menu') return $this->belongsTo(VariasiMenu::class, 'item_id');
        else if($this->tipe_item == 'bahan_dapur') return $this->belongsTo(BahanDapur::class, 'item_id');
        else if($this->tipe_item == 'barang') return $this->belongsTo(Barang::class, 'item_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::created(function ($model) {
            $items = $model->item;
            $items->stok = $model->stok_aktual;
            $items->save();           

            /// input ke inventori menu
            if($model->tipe_item == 'menu'){
                auth()->user()->bisnis->inventoriMenu()
                ->create([
                    'outlet_id' => $items->outlet_id,
                    'menu_id' => $items->menu_id,
                    'Variasi_menu_id' => $items->id_variasi_menu,
                    'kategori_menu_id' => $items->kategori_menu_id,
                    'tanggal' => date('Y-m-d'),
                    'stok_awal' => 0,
                    'penjualan' => 0,
                    'transfer' => 0,
                    'penyesuaian_Stok' => $model->selisih_stok,
                    'stok_akhir' => $model->stok_aktual
                ]);
            }

            /// input ke inventori Barang
            if($model->tipe_item == 'barang'){
                auth()->user()->bisnis->inventoriBarang()
                ->create([
                    'outlet_id' => $items->outlet_id,
                    'barang_id' => $items->id_barang,
                    'kategori_barang_id' => $items->kategori_barang_id,
                    'tanggal' => date('Y-m-d'),
                    'stok_awal' => 0,
                    'pemakaian' => 0,
                    'transfer' => 0,
                    'penyesuaian_Stok' => $model->selisih_stok,
                    'stok_akhir' => $model->stok_aktual
                ]);
            }

            /// input ke inventori Barang
            if($model->tipe_item == 'bahan_dapur'){
                auth()->user()->bisnis->inventoriBahanDapur()
                ->create([
                    'outlet_id' => $items->outlet_id,
                    'bahan_dapur_id' => $items->id_bahan_dapur,
                    'kategori_bahan_dapur_id' => $items->kategori_bahan_dapur_id,
                    'tanggal' => date('Y-m-d'),
                    'stok_awal' => 0,
                    'pemakaian' => 0,
                    'transfer' => 0,
                    'penyesuaian_Stok' => $model->selisih_stok,
                    'stok_akhir' => $model->stok_aktual
                ]);
            }
        });
    }
}
