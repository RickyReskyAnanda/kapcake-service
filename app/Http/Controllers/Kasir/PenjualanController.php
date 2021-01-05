<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Kasir\Penjualan as PenjualanResource;
use DB;
use App\Pemesanan;
use App\Http\Resources\Kasir\Menu as MenuResource;

class PenjualanController extends Controller
{
   
    public function index(Request $request)
    {
        $data = $request->user()->bisnis
                ->penjualan()
                ->with('item','item.menu.gambar')
                ->where('outlet_id',$request->outlet_id)
                ->where(function($q) use ($request){
                    $q->where(function($q) use ($request){
                        $q->where('tanggal_proses','=',$request->tanggal_max);
                        $q->where('waktu_proses','<=',$request->waktu_max);
                    });
                    $q->orWhere('tanggal_proses','<',$request->tanggal_max);

                })
                ->orderBy('tanggal_proses','desc')
                ->orderBy('waktu_proses','desc')
                ->orderBy('created_at','desc')
                ->skip($request->nomor_urut * 30)
                ->limit(30)
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => PenjualanResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

    /*
    |-----------------------------------------------------------------------------
    |                         FUNGSI STORE 
    |----------------------------------------------------------------------------
    |   1. cek data apakah ada, jika ada maka  update dan jika belum ada maka untuk kondisi yang belum diketahui
    |   2. 
    */

    public function store(Request $request){
        // $this->authorize('create', Barang::class);
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $returnMenus = [];
            if(isset($data['penjualan'])){

                $penjualan = $request->user()->bisnis
                                ->penjualan()
                                ->create(
                                    [
                                        'outlet_id' => $data['outlet_id'],
                                        'status' => $data['penjualan']['status'],
                                        'no_pemesanan' =>  $data['penjualan']['no_pemesanan'],
                                        'kode_pemesanan' =>  $data['penjualan']['kode_pemesanan'],
                                        'tanggal_simpan' =>  $data['penjualan']['tanggal_simpan'],
                                        'waktu_simpan' =>  $data['penjualan']['waktu_simpan'],
                                        'tanggal_proses' =>  $data['penjualan']['tanggal_proses'],
                                        'waktu_proses' =>  $data['penjualan']['waktu_proses'],

                                        'pelanggan_id' =>  (int)($data['penjualan']['pelanggan_id'] ) ?? 0,
                                        'nama_pelanggan' =>  $data['penjualan']['nama_pelanggan'] ?? '',
                                        'email_pelanggan' =>  $data['penjualan']['email_pelanggan'] ?? '',
                                        'no_hp_pelanggan' =>  $data['penjualan']['no_hp_pelanggan'] ?? '',

                                        'kategori_meja_id' =>  (int)($data['penjualan']['kategori_meja_id']) ?? 0,
                                        'nama_kategori_meja' =>  $data['penjualan']['nama_kategori_meja'] ?? null,

                                        'meja_id' =>  (int)($data['penjualan']['meja_id']) ?? 0,
                                        'nama_meja' =>  $data['penjualan']['nama_meja'] ?? '',
                                        
                                        'subtotal' =>  (float)($data['penjualan']['subtotal']),
                                        // diskon
                                        'diskon_id' =>  (int)($data['penjualan']['diskon_id']),
                                        'nama_diskon' =>  $data['penjualan']['nama_diskon'],
                                        'jumlah_diskon' =>  (float)($data['penjualan']['jumlah_diskon']),
                                        'total_diskon' =>  (float)($data['penjualan']['total_diskon']),
                                        //biaya tambahan
                                        'biaya_tambahan_id' =>  (int)($data['penjualan']['biaya_tambahan_id']),
                                        'nama_biaya_tambahan' =>  $data['penjualan']['nama_biaya_tambahan'],
                                        'jumlah_biaya_tambahan' =>  (float)($data['penjualan']['jumlah_biaya_tambahan']),
                                        'total_biaya_tambahan' =>  (float)($data['penjualan']['total_biaya_tambahan']),
                                        //pajak
                                        'pajak_id' =>  (int)($data['penjualan']['pajak_id']),
                                        'nama_pajak' =>  $data['penjualan']['nama_pajak'],
                                        'jumlah_pajak' =>  (float)($data['penjualan']['jumlah_pajak']),
                                        'total_pajak' =>  (float)($data['penjualan']['total_pajak']),

                                        'total_item' =>  (float)($data['penjualan']['total_item']),
                                        'total' =>  (float)($data['penjualan']['total']),
                                        'jumlah_pembayaran' =>  (float)($data['penjualan']['jumlah_pembayaran']),
                                        'metode_pembayaran' =>  $data['penjualan']['metode_pembayaran'],
                                        'kembalian' =>  (float)($data['penjualan']['kembalian']),
                                    ]);
                foreach ($data['penjualan']['item'] as $i) {
                    $item = $penjualan
                        ->item()
                        ->create([
                            'outlet_id' => $data['outlet_id'],
                            'unique_id' => $i['unique_id'],
                            'menu_id' => (int)$i['menu_id'],
                            'nama_menu' => $i['nama_menu'],
                            'variasi_menu_id' => (int)$i['variasi_menu_id'],
                            'nama_variasi_menu' => $i['nama_variasi_menu'],
                            'tipe_penjualan_id' => (int)$i['tipe_penjualan_id'],
                            'nama_tipe_penjualan' => $i['nama_tipe_penjualan'],
                            'kategori_menu_id' => $i['kategori_menu_id'] ?? 0,
                            'nama_kategori_menu' => $i['nama_kategori_menu'] ?? null,
                            'jumlah' => (float)$i['jumlah'],
                            'harga' => (float)$i['harga'],
                            'total' => (float)$i['total'],
                            'catatan' =>  $i['catatan']
                        ]);
                }

                Pemesanan::where('kode_pemesanan',$data['penjualan']['kode_pemesanan'])->delete();


                $returnMenus = $this->getUpdatedDataMenu($request, $data['penjualan']['item']);
            }

            DB::commit();
            return response([
                'status' => 'success',
                'data' => MenuResource::collection($returnMenus),
                'message' => ['Berhasil menyimpan data riwayat']
            ], 200);
            return response($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' => ['Terjadi Kesalahan']
            ], 500);
        }
    }


    public function show(Request $request){
        $filter = $request->validate([
            'outlet_id' => 'required|integer',
            'kode_pemesanan' => 'required|string'
        ]);

        $data = $request->user()->bisnis
                ->penjualan()
                ->with('item', 'item.menu.gambar')
                ->where('outlet_id', $filter['outlet_id'])
                ->where('kode_pemesanan', $filter['kode_pemesanan'])
                ->first();

        return response()->json([
            'status' => 'success',
            'data' => new PenjualanResource($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

    private function validation(){
        return [
            'outlet_id' => 'required|integer',
            'penjualan' => 'required',
        ];
    }


    private function getMenuIdFromItems($items){
        $menuId = [];
        foreach ($items as $key => $v) {
            array_push($menuId, $v['menu_id']);
        }
        return $menuId;
    }

    private function getUpdatedDataMenu($request, $items){
        $menuId = $this->getMenuIdFromItems($items);
        return $request->user()->bisnis
            ->menu()
            ->with(['tipePenjualan' => function($q){
                $q->with('tipePenjualan');
                $q->whereHas('tipePenjualan',function($q){
                    $q->where('is_aktif',1);
                });
            }, 'gambar','variasi.tipePenjualan','kategori'])
            ->where('outlet_id', $request->outlet_id ?? 0)
            ->whereIn('id_menu', $menuId)
            ->get();
    }
}
