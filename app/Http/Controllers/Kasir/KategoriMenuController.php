<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Kasir\Menu as MenuResource;

class KategoriMenuController extends Controller
{
    public function index(Request $request){
        $filter = $request->validate([
            'outlet_id' => 'required|integer',
        ]);

        $data = $request->user()->bisnis
            ->kategoriMenu()
            ->where('outlet_id', $filter['outlet_id'])
            ->has('menu')
            ->orderBy('nama_kategori_menu','asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

}
