<?php

namespace App\Http\Controllers\KasirPotrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KasirPotrait\Meja as MejaResource;
use App\Http\Resources\KasirPotrait\KategoriMeja as KategoriMejaResource;

class MejaController extends Controller
{
    public function index(Request $request){

        $meja = $request->user()->bisnis
                ->meja()
                ->where('outlet_id', $request->outlet_id)
                ->get();
        $kategoriMeja = $request->user()->bisnis
                ->kategoriMeja()
                ->where('outlet_id', $request->outlet_id)
                ->where('is_aktif', 1)
                ->get();
        return response()->json([
            'status' => 'success',
            'data' => [
            	'meja' => MejaResource::collection($meja),
            	'kategori_meja' => KategoriMejaResource::collection($kategoriMeja),
            ],
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }
}
