<?php

namespace App\Http\Controllers\KasirPotrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SatuanController extends Controller
{
    public function index(Request $request){
        $data = $request->user()->bisnis
                ->satuan()
                ->select('id_satuan','satuan')
                ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }
}
