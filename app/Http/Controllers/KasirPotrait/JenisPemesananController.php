<?php

namespace App\Http\Controllers\KasirPotrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KasirPotrait\JenisPemesanan as JenisPemesananResource;

class JenisPemesananController extends Controller
{
    public function index(Request $request){

        $data = $request->user()->bisnis
                ->tipePenjualan()
                ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id: 0)
                ->where('is_aktif', 1)
                ->get();
        return response()->json([
            'status' => 'success',
            'data' => JenisPemesananResource::collection($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }
}
