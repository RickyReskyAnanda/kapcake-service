<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\InventoriMenu as InventoriMenuResource;
use App\Http\Resources\InventoriBahanDapur as InventoriBahanDapurResource;
use App\Http\Resources\InventoriBarang as InventoriBarangResource;
use DB;
use App\InventoriMenu;
use App\InventoriBarang;
use App\InventoriBahanDapur;

class InventoriController extends Controller
{
    public function index(Request $request){
        $user = $request->user();

        if($request->has('jenis_item'))
        	if($request->jenis_item == 'menu'){
                $data = InventoriMenu::select(
                            DB::raw("
                                menu.nama_menu, 
                                if(variasi_menu.nama_variasi_menu IS NOT NULL, variasi_menu.nama_variasi_menu, 'Tanpa   Variasi') AS nama_variasi_menu,
                                (   SELECT stok_awal
                                    FROM inventori_menu a1 
                                    WHERE a1.menu_id = inventori_menu.menu_id
                                        AND a1.variasi_menu_id = inventori_menu.variasi_menu_id
                                        AND a1.bisnis_id = inventori_menu.bisnis_id 
                                        AND a1.outlet_id  = inventori_menu.outlet_id "
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '".$request->tanggal_awal." 00:00:00'       
                                        AND a1.created_at <= '".$request->tanggal_akhir." 23:59:59'" : "").
                                    " ORDER BY created_at asc
                                    LIMIT 1              
                                ) as stok_awal,
                                SUM(pesanan_pembelian) AS pesanan_pembelian,
                                SUM(penjualan) AS penjualan,
                                SUM(penyesuaian_stok) AS penyesuaian_stok,
                                SUM(transfer) AS transfer,
                                (SELECT stok_akhir
                                    FROM inventori_menu a1 
                                    WHERE a1.menu_id = inventori_menu.menu_id
                                        AND a1.variasi_menu_id = inventori_menu.variasi_menu_id
                                        AND a1.bisnis_id = inventori_menu.bisnis_id 
                                        AND a1.outlet_id  = inventori_menu.outlet_id "
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '".$request->tanggal_awal." 00:00:00'       
                                        AND a1.created_at <= '".$request->tanggal_akhir." 23:59:59'" : "").
                                    " ORDER BY created_at desc
                                    LIMIT 1              
                                ) as stok_akhir
                            ")
                        )
                        ->leftJoin('menu', 'inventori_menu.menu_id', '=', 'menu.id_menu')
                        ->leftJoin('variasi_menu', 'inventori_menu.variasi_menu_id', '=', 'variasi_menu.id_variasi_menu')
                        ->where(function($q) use ($user,$request){
                            $q->whereNotNull('menu.nama_menu');
                            $q->where('inventori_menu.bisnis_id', $user['bisnis_id']);

                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('inventori_menu.outlet_id', $request->outlet_id);

                            if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                                $q->whereBetween('inventori_menu.created_at', [$request->tanggal_awal.' 00:00:00', $request->tanggal_akhir.' 23:59:59']);
                        })
                        ->groupBy('nama_menu',  'nama_variasi_menu')
                        ->orderBy('nama_menu','asc')
                        ->orderBy('nama_variasi_menu','asc')
                        ->paginate(10);
    	    	return InventoriMenuResource::collection($data);
        	}elseif($request->jenis_item == 'bahan_dapur'){
        		$data = InventoriBahanDapur::select(
                            DB::raw("
                                bahan_dapur.nama_bahan_dapur, 
                                (   SELECT stok_awal
                                    FROM inventori_bahan_dapur a1 
                                    WHERE a1.bahan_dapur_id = inventori_bahan_dapur.bahan_dapur_id
                                        AND a1.bisnis_id = inventori_bahan_dapur.bisnis_id 
                                        AND a1.outlet_id  = inventori_bahan_dapur.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    " ORDER BY created_at asc
                                    LIMIT 1              
                                ) as stok_awal,
                                SUM(pesanan_pembelian) AS pesanan_pembelian,
                                SUM(pemakaian) AS pemakaian,
                                SUM(penyesuaian_stok) AS penyesuaian_stok,
                                SUM(transfer) AS transfer,
                                (SELECT stok_akhir
                                    FROM inventori_bahan_dapur a1 
                                    WHERE a1.bahan_dapur_id = inventori_bahan_dapur.bahan_dapur_id
                                        AND a1.bisnis_id = inventori_bahan_dapur.bisnis_id 
                                        AND a1.outlet_id  = inventori_bahan_dapur.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    " ORDER BY created_at desc
                                    LIMIT 1              
                                ) as stok_akhir
                            ")
                        )
                        ->leftJoin('bahan_dapur', 'inventori_bahan_dapur.bahan_dapur_id', '=', 'bahan_dapur.id_bahan_dapur')
                        ->where(function($q) use ($user,$request){
                            $q->whereNotNull('bahan_dapur.nama_bahan_dapur');
                            $q->where('inventori_bahan_dapur.bisnis_id', $user['bisnis_id']);
                            $q->whereNotNull('nama_bahan_dapur');

                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('inventori_bahan_dapur.outlet_id', $request->outlet_id);

                            if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                                $q->whereBetween('inventori_bahan_dapur.created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
                        })
                        ->groupBy('nama_bahan_dapur')
                        ->orderBy('nama_bahan_dapur','asc')
                        ->paginate(10);
    	    	
    	    	return InventoriBahanDapurResource::collection($data);
        	}elseif($request->jenis_item == 'barang'){
        		$data = InventoriBarang:: select(
                            DB::raw("
                                barang.nama_barang, 
                                (   SELECT stok_awal
                                    FROM inventori_barang a1 
                                    WHERE a1.barang_id = inventori_barang.barang_id
                                        AND a1.bisnis_id = inventori_barang.bisnis_id 
                                        AND a1.outlet_id  = inventori_barang.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    " ORDER BY created_at asc
                                    LIMIT 1              
                                ) as stok_awal,
                                SUM(pesanan_pembelian) AS pesanan_pembelian,
                                SUM(pemakaian) AS pemakaian,
                                SUM(penyesuaian_stok) AS penyesuaian_stok,
                                SUM(transfer) AS transfer,
                                (SELECT stok_akhir
                                    FROM inventori_barang a1 
                                    WHERE a1.barang_id = inventori_barang.barang_id
                                        AND a1.bisnis_id = inventori_barang.bisnis_id 
                                        AND a1.outlet_id  = inventori_barang.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    " ORDER BY created_at desc
                                    LIMIT 1              
                                ) as stok_akhir
                            ")
                        )
                        ->leftJoin('barang', 'inventori_barang.barang_id', '=', 'barang.id_barang')
                        ->where(function($q) use ($user,$request){
                            $q->whereNotNull('barang.nama_barang');
                            $q->where('inventori_barang.bisnis_id', $user['bisnis_id']);
                            $q->whereNotNull('nama_barang');

                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('inventori_barang.outlet_id', $request->outlet_id);

                            if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                                $q->whereBetween('inventori_barang.created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
                        })
                        ->groupBy('nama_barang')
                        ->orderBy('nama_barang','asc')
                        ->paginate(10);
    	    	
    	    	return InventoriBarangResource::collection($data);
        	}
    }
}
