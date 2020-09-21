<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Kasir\Menu as MenuResource;

class MenuController extends Controller
{
    /**
    * Seleksi data agar hanya mengambil yang variasinya memiliki harga
    *
    */
    public function index(Request $request){
        $filter = $request->validate([
            'outlet_id' => 'required|integer',
            'nomor_urut' => 'required|integer'
        ]);
        $data = $request->user()->bisnis
                ->menu()
                ->with(['tipePenjualan' => function($q){
                    $q->with('tipePenjualan');
                    $q->whereHas('tipePenjualan',function($q){
                        $q->where('is_aktif',1);
                    });
                }, 'gambar','variasi.tipePenjualan','kategori'])
                ->where('outlet_id', $filter['outlet_id'])
                ->orderBy('nama_menu', 'asc')
                ->skip($filter['nomor_urut'] * 30)
                ->limit(30)
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => MenuResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

}
