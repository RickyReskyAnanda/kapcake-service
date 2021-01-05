<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Outlet;
use App\Http\Resources\TokoOnline\Outlet as OutletResource;
class ProfileController extends Controller
{
    public function index($kodeToko,Request $request)
    {   
        $data = Outlet::with('bisnis')->where('kode_toko_online',$kodeToko)
                    ->first();

       
        return response()->json([
            'status' => 'success',
            'data' => new OutletResource($data),
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }
}
