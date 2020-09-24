<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Outlet;

class ProfileController extends Controller
{
    public function index($kodeToko,Request $request)
    {   
        $data = Outlet::where('kode_toko_online',$kodeToko)
                    ->first();
       
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'total' => count($data),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }
}
