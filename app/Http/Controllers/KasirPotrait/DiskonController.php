<?php

namespace App\Http\Controllers\KasirPotrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KasirPotrait\Diskon as DiskonResource;

class DiskonController extends Controller
{
    public function index(Request $request){

        $data = $request->user()->bisnis
                ->diskon()
                ->where('outlet_id', $request->outlet_id)
                ->get();

		return response()->json([
            'status' => 'success',
            'data' => DiskonResource::collection($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }
}
