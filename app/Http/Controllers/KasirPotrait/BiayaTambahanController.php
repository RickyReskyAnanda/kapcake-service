<?php

namespace App\Http\Controllers\KasirPotrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KasirPotrait\BiayaTambahan as BiayaTambahanResource;

class BiayaTambahanController extends Controller
{
    public function index(Request $request){

        $data = $request->user()->bisnis
                ->biayaTambahan()
                ->where('outlet_id', $request->outlet_id)
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => BiayaTambahanResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }
}
