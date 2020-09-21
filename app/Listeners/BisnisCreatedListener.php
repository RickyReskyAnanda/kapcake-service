<?php

namespace App\Listeners;
use DB;
use App\KategoriMenu;
use App\KategoriBahanDapur;
use App\KategoriBarang;
use App\KategoriMeja;
use App\Meja;
use App\BiayaTambahan;
use App\Pajak;
use App\Diskon;
use App\TipePenjualan;
use App\Perangkat;
use App\Outlet;
use App\OutletUser;
use App\Role;
use App\Aplikasi;
use App\Satuan;
use App\Events\BisnisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BisnisCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BisnisCreated  $event
     * @return void
     */
    public function handle(BisnisCreated $event)
    {
        DB::beginTransaction();
        try {
            $outlet = Outlet::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'nama_outlet' => 'Outlet 1',
                'provinsi' => $event->bisnis->provinsi,
                'kode_pos' => '00000',
            ]);
            KategoriMenu::insert([
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'is_paten' => 1,
                    'nama_kategori_menu' => 'Tanpa Kategori',
                ],
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'is_paten' => 0,
                    'nama_kategori_menu' => 'Minuman',
                ],

                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'is_paten' => 0,
                    'nama_kategori_menu' => 'Makanan',
                ],
            ]);
            
            KategoriBahanDapur::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'is_paten' => 1,
                'nama_kategori_bahan_dapur' => 'Tanpa Kategori',
            ]);

            KategoriBarang::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'is_paten' => 1 ,
                'nama_kategori_barang' => 'Tanpa Kategori',
            ]);

            $kategoriMeja = KategoriMeja::create(
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'nama_kategori_Meja' => 'Tanpa Kategori',
                    'is_aktif' => 1,
                    'is_paten' => 1,
                ]);
            KategoriMeja::insert([
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'nama_kategori_Meja' => 'Lantai 1',
                    'is_aktif' => 1,
                    'is_paten' => 1,
                ],
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'nama_kategori_Meja' => 'Smoking Area',
                    'is_aktif' => 1,
                    'is_paten' => 1,
                ]
            ]);

            Meja::insert([
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'kategori_meja_id' => $kategoriMeja->id_kategori_meja,
                    'nama_meja' => 'A1',
                    'pax' => 4,
                    'bentuk' => 'persegi',
                ],[
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'kategori_meja_id' => $kategoriMeja->id_kategori_meja,
                    'nama_meja' => 'A2',
                    'pax' => 4,
                    'bentuk' => 'persegi',
                ],
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'kategori_meja_id' => $kategoriMeja->id_kategori_meja,
                    'nama_meja' => 'A3',
                    'pax' => 4,
                    'bentuk' => 'persegi',
                ],
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'kategori_meja_id' => $kategoriMeja->id_kategori_meja,
                    'nama_meja' => 'A4',
                    'pax' => 4,
                    'bentuk' => 'persegi',
                ],
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'kategori_meja_id' => $kategoriMeja->id_kategori_meja,
                    'nama_meja' => 'A5',
                    'pax' => 4,
                    'bentuk' => 'persegi',
                ]
            ]);

            BiayaTambahan::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'nama_biaya_tambahan' => 'Service Charge' ,
                'jumlah' => 2.5,
            ]);

            Pajak::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'nama_pajak' => 'PPN' ,
                'jumlah' => 10 ,
            ]);

            Diskon::insert([
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'nama_diskon' => 'Promo 5%',
                    'jumlah' => 5,
                    'tipe_diskon' => 'persen' 
                ],[
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'nama_diskon' => 'Promo 10%',
                    'jumlah' => 10,
                    'tipe_diskon' => 'persen'
                ]
            ]);

            TipePenjualan::insert([
                [
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'nama_tipe_penjualan' => 'Dine In',
                    'is_aktif' => 1,
                    'is_paten' => 1 
                ],[
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'outlet_id' => $outlet->id_outlet,
                    'nama_tipe_penjualan' => 'Take Away',
                    'is_aktif' => 1,
                    'is_paten' => 0 
                ]
            ]);

            Perangkat::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'nama_perangkat' => 'Perangkat 1' ,
                'is_aktif' => 0 ,
                'cetak_salinan_struk' => 0,
                'teruskan_ke_printer_kasir' => 0
            ]);

            $satuan = [
                'Buah', 
                'Kilogram', 
                'Gram',
                'Miligram',
                'Ekor',
                'Liter',
                'Ikat',
                'Dus',
                'Bungkus',
                'Lembar',
                'Pon',
                'Rak',
                'Butir',
                'Galon',
                'Unit',
                'Pasang',
                'Keping',
                'Rim',
                'Lusin',
                'Kodi',
                'Sak',
                'Gulung',
                'Botol',
                'Batang',
                'Potong',
                'Tablet',
                'Karung',
                'Set',
                'Meter',
            ];

            foreach($satuan as $s){
                Satuan::create([
                    'bisnis_id' => $event->bisnis->id_bisnis,
                    'satuan' => $s
                ]);
            }

            OutletUser::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'user_id' => $event->bisnis->user_id ,
            ]);

            $administrator = Role::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'nama_role' => 'Administrator',
                'is_paten' => 1
            ]);

            $aplikasi = Aplikasi::with('otorisasi','otorisasi.child')->get();
            foreach($aplikasi as $a){
                $app = $administrator
                    ->aplikasi()
                    ->create([
                        'bisnis_id' => $event->bisnis->id_bisnis,
                        'aplikasi_id' => $a->id_aplikasi,
                        'is_aktif' => 1
                    ]);

                foreach($a->otorisasi as $otorisasi){
                    $oto = $app
                    ->otorisasi()
                    ->create([
                        'bisnis_id' => $event->bisnis->id_bisnis,
                        'role_id' => $app->role_id,
                        'parent_id' => 0,
                        'otorisasi_id' => $otorisasi->id_otorisasi,
                        'is_aktif' => $otorisasi->is_aktif,
                    ]);

                    foreach ($otorisasi->child as $child) {
                        $oto
                        ->child()
                        ->create([
                            'bisnis_id' => $event->bisnis->id_bisnis,
                            'role_id' => $app->role_id,
                            'role_aplikasi_id' => $oto->role_aplikasi_id,
                            'otorisasi_id' => $child->id_otorisasi,
                            'is_aktif' => $otorisasi->is_aktif,
                        ]);
                    }

                }
            }

            $kasir = Role::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'nama_role' => 'Kasir',
                'is_paten' => 1
            ]);


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response('error', 500);
        }
    }
}
