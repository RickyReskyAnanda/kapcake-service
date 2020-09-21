<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Outlet;
use App\Http\Resources\TokoOnline\KategoriProduk as KategoriProdukResource;
class KategoriProdukController extends Controller
{
    public function index($kodeToko,Request $request){
        // $filter = $request->validate([
            // 'kode_toko' => 'required|string',
        // ]);

        // return $kodeToko;
        $outlet = Outlet::select('id_outlet')
                                ->where('kode_toko_online',$kodeToko)
                                ->first();
        
        $data  = $outlet->kategoriMenu()
                ->orderBy('nama_kategori_menu','asc')
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => KategoriProdukResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

}
