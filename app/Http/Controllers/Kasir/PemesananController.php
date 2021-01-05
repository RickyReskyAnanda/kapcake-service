<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Resources\Kasir\Pesanan as PesananResource;

class PemesananController extends Controller
{
    public function index(Request $request){
        $filter = $request->validate([
            'outlet_id' => 'required|integer',
            'nomor_urut' => 'required|integer',
            'jumlah' => 'nullable|integer',
            'waktu_max' => 'required',
            'tanggal_max' => 'required',
            
        ]);
        $data = $request->user()->bisnis
                ->pemesanan()
                ->with('item.menu.gambar')
                ->where('outlet_id', $filter['outlet_id'])
                ->where(function($q) use ($request,$filter){
                    $q->where('tanggal_simpan','=',$filter['tanggal_max']);
                    $q->where('waktu_simpan','<=',$filter['waktu_max']);
                    $q->orWhere('tanggal_simpan','<',$filter['tanggal_max']);
                })
                ->orderBy('tanggal_simpan','desc')
                ->orderBy('waktu_simpan','desc')
                ->orderBy('id_pemesanan','desc')
                ->skip($request->nomor_urut * 30)
                ->limit(30)
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => PesananResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

    public function store(Request $request){
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try { 
            if(isset($data['pemesanan'])){
                $pemesanan = $request->user()->bisnis
                                ->pemesanan()
                                ->updateOrCreate(
                                    ['kode_pemesanan' =>  $data['pemesanan']['kode_pemesanan']],
                                    [
                                    'outlet_id' => $data['outlet_id'],
                                    'no_pemesanan' =>  $data['pemesanan']['no_pemesanan'],
                                    'kode_pemesanan' =>  $data['pemesanan']['kode_pemesanan'],
                                    'tanggal_simpan' =>  $data['pemesanan']['tanggal_simpan'],
                                    'waktu_simpan' =>  $data['pemesanan']['waktu_simpan'],
                                    'tanggal_proses' =>  $data['pemesanan']['tanggal_proses'],
                                    'waktu_proses' =>  $data['pemesanan']['waktu_proses'],
                                    'pelanggan_id' =>  (int)($data['pemesanan']['pelanggan_id']),
                                    'nama_pelanggan' =>  $data['pemesanan']['nama_pelanggan'],
                                    'email_pelanggan' =>  $data['pemesanan']['email_pelanggan'],
                                    'no_hp_pelanggan' =>  $data['pemesanan']['no_hp_pelanggan'],
                                    'nama_pelanggan' =>  $data['pemesanan']['nama_pelanggan'],
                                    'kategori_meja_id' =>  (int)($data['pemesanan']['kategori_meja_id']),
                                    'nama_kategori_meja' =>  $data['pemesanan']['nama_kategori_meja'],
                                    'meja_id' =>  (int)($data['pemesanan']['meja_id']),
                                    'nama_meja' =>  $data['pemesanan']['nama_meja'],
                                    'diskon_id' =>  (int)($data['pemesanan']['diskon_id']),
                                    'nama_diskon' =>  $data['pemesanan']['nama_diskon'],
                                    'jumlah_diskon' =>  (float)($data['pemesanan']['jumlah_diskon']),
                                    'tipe_diskon' =>  (float)($data['pemesanan']['tipe_diskon']),
                                    'total_diskon' =>  (float)($data['pemesanan']['total_diskon']),
                                    'biaya_tambahan_id' =>  (int)($data['pemesanan']['biaya_tambahan_id']),
                                    'nama_biaya_tambahan' =>  $data['pemesanan']['nama_biaya_tambahan'],
                                    'jumlah_biaya_tambahan' =>  (float)($data['pemesanan']['jumlah_biaya_tambahan']),
                                    'total_biaya_tambahan' =>  (float)($data['pemesanan']['total_biaya_tambahan']),
                                    'pajak_id' =>  (int)($data['pemesanan']['pajak_id']),
                                    'nama_pajak' =>  $data['pemesanan']['nama_pajak'],
                                    'jumlah_pajak' =>  (float)($data['pemesanan']['jumlah_pajak']),
                                    'total_pajak' =>  (float)($data['pemesanan']['total_pajak']),
                                    'subtotal' =>  (float)($data['pemesanan']['subtotal']),
                                    'total_item' =>  (float)($data['pemesanan']['total_item']),
                                    'total' =>  (float)($data['pemesanan']['total'])
                                ]);
                foreach ($data['pemesanan']['pesanan'] as $i) {
                    $pemesanan
                        ->item()
                        ->updateOrCreate(
                        [   
                            'unique_id' => $i['unique_id'],
                            'menu_id' =>(int)$i['menu_id']
                        ],
                        [
                            'unique_id' => $i['unique_id'],
                            'outlet_id' => $data['outlet_id'],
                            'menu_id' => (int)$i['menu_id'],
                            'nama_menu' => $i['nama_menu'],
                            'variasi_menu_id' => (int)$i['variasi_menu_id'],
                            'nama_variasi_menu' => $i['nama_variasi_menu'],
                            'tipe_penjualan_id' => (int)$i['tipe_penjualan_id'],
                            'nama_tipe_penjualan' => $i['nama_tipe_penjualan'],
                            'jumlah' => (float)$i['jumlah'],
                            'harga' => (float)$i['harga'],
                            'total' => (float)$i['total'],
                            'catatan' =>  $i['catatan']
                        ]);
                }
            }
            DB::commit();
            return response([
                'status' => 'success',
                'message' => ['Berhasil menyimpan data pesanan']
            ],200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Request $request){
        $filter = $request->validate([
            'outlet_id' => 'required|integer',
            'kode_pemesanan' => 'required|string'
        ]);
        
        $data = $request->user()->bisnis
                ->pemesanan()
                ->with('item.menu.gambar')
                ->where('outlet_id', $filter['outlet_id'])
                ->where('kode_pemesanan', $filter['kode_pemesanan'])
                ->first();

        return response()->json([
            'status' => 'success',
            'data' => new PesananResource($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

    private function validation(){
        return [
            'outlet_id' => 'required|integer',
            'pemesanan.no_pemesanan' => 'required',
            'pemesanan.kode_pemesanan' => 'required',
            'pemesanan.tanggal_simpan' => 'required',
            'pemesanan.waktu_simpan' => 'required',
            'pemesanan.tanggal_proses' => 'nullable',
            'pemesanan.waktu_proses' => 'nullable',
            'pemesanan.pelanggan_id' => 'nullable|integer',
            'pemesanan.nama_pelanggan' => 'nullable|string',
            'pemesanan.email_pelanggan' => 'nullable|string',
            'pemesanan.no_hp_pelanggan' => 'nullable|string',
            'pemesanan.kategori_meja_id' => 'nullable|integer',
            'pemesanan.nama_kategori_meja' => 'nullable|string',
            'pemesanan.meja_id' => 'nullable|integer',
            'pemesanan.nama_meja' => 'nullable|string',
            'pemesanan.diskon_id' => 'nullable|integer',
            'pemesanan.nama_diskon' => 'nullable|string',
            'pemesanan.jumlah_diskon' => 'nullable|numeric',
            'pemesanan.tipe_diskon' => 'nullable|string',
            'pemesanan.total_diskon' => 'nullable|numeric',
            'pemesanan.biaya_tambahan_id' => 'nullable|integer',
            'pemesanan.nama_biaya_tambahan' => 'nullable|string',
            'pemesanan.jumlah_biaya_tambahan' => 'nullable|numeric',
            'pemesanan.total_biaya_tambahan' => 'nullable|numeric',
            'pemesanan.pajak_id' => 'nullable|integer',
            'pemesanan.nama_pajak' => 'nullable|string',
            'pemesanan.jumlah_pajak' => 'nullable|numeric',
            'pemesanan.total_pajak' => 'nullable|numeric',
            'pemesanan.subtotal' => 'required|numeric',
            'pemesanan.total_item' => 'required|numeric',
            'pemesanan.total' => 'required|integer',
            'pemesanan.pesanan' => 'required',
        ];
    }
}
