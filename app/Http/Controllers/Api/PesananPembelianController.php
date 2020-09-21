<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\PesananPembelian;
use App\PesananPembelianEntry;
use App\Http\Resources\PesananPembelianIndex as PesananPembelianIndexResource;
use App\Http\Resources\PesananPembelianShow as PesananPembelianShowResource;


class PesananPembelianController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', PesananPembelian::class);

        if(isset($request->paginate) && $request->paginate == 'true'){
            $data = $request->user()->bisnis
                    ->pesananPembelian()
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id !== '0' )
                            $q->where('outlet_id', $request->outlet_id);
                        if($request->has('jenis_item'))
                            $q->where('tipe_item', $request->jenis_item);
                        if($request->has('status_pesanan_pembelian') && $request->status_pesanan_pembelian != '')
                            $q->where('status', $request->status_pesanan_pembelian);
                        if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                            $q->whereBetween('created_at', [$request->tanggal_awal.' 00:00:00', $request->tanggal_akhir.' 23:59:59']);
                        if($request->has('pencarian') && $request->pencarian != '')
                            $q->where('id_pesanan_pembelian', 'like','%'.$request->pencarian.'%');
                    })
                    ->latest()
                    ->paginate(10);
       
            return PesananPembelianIndexResource::collection($data);
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', PesananPembelian::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $pesananPembelian = $request->user()->bisnis
                            ->pesananPembelian()
                            ->create($data['data']);
            
            $hargaTotal = 0;
            foreach ($data['entry'] as $d) {
                $entry = $pesananPembelian
                    ->entry()
                    ->create($d);
                $hargaTotal +=(float)$entry->harga_total;
                
                if($pesananPembelian->status == 'selesai'){
                    $item = $entry->item;
                    $item->stok = $item->stok + $entry->stok_dipesan;
                    $item->save();

                    /// input ke inventori menu
                    if($d['tipe_item'] == 'menu'){
                        $request->user()->bisnis->inventoriMenu()
                        ->create([
                            'outlet_id' => $data['data']['outlet_id'],
                            'menu_id' => $item['menu_id'],
                            'variasi_menu_id' => $item['id_variasi_menu'],
                            'kategori_menu_id' => $item['kategori_menu_id'],
                            'tanggal' => date('Y-m-d'),
                            'stok_awal' => 0,
                            'pesanan_pembelian' => $entry->stok_dipesan,
                            'penjualan' => 0,
                            'transfer' => 0,
                            'penyesuaian_stok' => 0,
                            'stok_akhir' => $item->stok
                        ]);
                    }

                    /// input ke inventori Barang
                    if($d['tipe_item'] == 'barang'){
                        $request->user()->bisnis->inventoriBarang()
                        ->create([
                            'outlet_id' => $data['data']['outlet_id'],
                            'barang_id' => $item['id_barang'],
                            'kategori_barang_id' => $item['kategori_barang_id'],
                            'tanggal' => date('Y-m-d'),
                            'stok_awal' => 0,
                            'pesanan_penjualan' => $entry->stok_dipesan,
                            'pemakaian' => 0,
                            'transfer' => 0,
                            'penyesuaian_stok' =>0,
                            'stok_akhir' => $item->stok
                        ]);
                    }

                    /// input ke inventori Barang
                    if($d['tipe_item'] == 'bahan_dapur'){
                        $request->user()->bisnis->inventoriBahanDapur()
                        ->create([
                            'outlet_id' => $data['data']['outlet_id'],
                            'bahan_dapur_id' => $item['id_bahan_dapur'],
                            'kategori_bahan_dapur_id' => $item['kategori_bahan_dapur_id'],
                            'tanggal' => date('Y-m-d'),
                            'stok_awal' => 0,
                            'pesanan_penjualan' => $entry->stok_dipesan,
                            'pemakaian' => 0,
                            'transfer' => 0,
                            'penyesuaian_stok' =>0,
                            'stok_akhir' => $item->stok
                        ]);
                    }
                }
            }

            $pesananPembelian->update([ 'total' => $hargaTotal]);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Pesanan pembelian berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(PesananPembelian $pesananPembelian)
    {
        $this->authorize('show', $pesananPembelian);
        $pesananPembelian->load('entry');
        return new PesananPembelianShowResource($pesananPembelian);
    }

    public function update(Request $request, PesananPembelian $pesananPembelian)
    {
        $this->authorize('update', $pesananPembelian);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $idEntry = [];
            $hargaTotal = 0;
            foreach ($data['entry'] as $d) {
            	$entry = [];
            	if(isset($d['id'])){
	                $entry =$pesananPembelian
                        ->entry()
                        ->findOrFail($d['id']);
                    $entry->update([
                            'item_id' => $d['item_id'],
                            'tipe_item' => $d['tipe_item'],
                            'stok' => $d['stok'],
                            'stok_dipesan' => $d['stok_dipesan'],
                            'harga_satuan' => $d['harga_satuan'],
                            'harga_total' => $d['harga_total'],
                        ]);
            	}else{
	            	$entry = $pesananPembelian
	                    ->entry()
	                    ->create([
                            'item_id' => $d['item_id'],
                            'tipe_item' => $d['tipe_item'],
                            'stok' => $d['stok'],
                            'stok_dipesan' => $d['stok_dipesan'],
                            'harga_satuan' => $d['harga_satuan'],
                            'harga_total' => $d['harga_total'],
                        ]);
                }
                array_push($idEntry, $entry['id_pesanan_pembelian_entry']); 
                $hargaTotal += $d['harga_total'];
            }

            $pesananPembelian
                ->entry()
                ->whereNotIn('id_pesanan_pembelian_entry', $idEntry)
                ->delete();

            $pesananPembelian->update($data['data'] + ['total' => $hargaTotal]);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Pesanan pembelian berhasil diperbaharui"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function validation(){
        return [
            'data' => 'required',
            'entry' => 'required',
        ];
    }
}
