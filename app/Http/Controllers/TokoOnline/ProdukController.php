<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Outlet;
use App\Http\Resources\TokoOnline\Produk as ProdukResource;
class ProdukController extends Controller
{
    public function index($kodeToko, Request $request){
        $filter = $request->validate([
            'offset' => 'required|integer',
            'limit' => 'required|integer',
            'search' => 'nullable|string',
        ]);

        $outlet = Outlet::select('id_outlet')
                    ->where('kode_toko_online',$kodeToko)
                    ->first();
        $data  = $outlet->menu()
                ->with('variasi','thumbGambar')
                ->where(function($q) use ($filter){
                    if(isset($filter['search']))
                    $q->where('nama_menu', 'like', '%' . $filter['search'] . '%');
                })
                ->orderBy('nama_menu','asc')
                ->skip($filter['offset'])
                ->take($filter['limit'])
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => ProdukResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

    public function show($id,$kodeToko,Request $request){
        $outlet = Outlet::select('id_outlet')
                    ->where('kode_toko_online',$kodeToko)
                    ->first();
        $data  = $outlet->menu()
                ->with('variasi','gambar')
                ->where('id_menu',$id)
                ->first();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            // 'data' => ProdukResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

}
