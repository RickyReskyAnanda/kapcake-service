<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProfileController extends Controller
{
    public function index(Request $request)
    {   
        $data = [];

        $data['kategori_produkl'] = $this->getKategoriProduk($request);

        return $data;
    }


    private function getKategoriProduk($request){

        // try {   
        
            return $request->user()->bisnis
                    ->kategoriMenu()
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
                            $q->where('outlet_id', $request->outlet_id);
                    })
                    ->orderBy('created_at','desc')
                    ->get();

        // } catch (\Exception $e) {
        //     return response([
        //         'status' => 'error',
        //         'message' =>  ["Terjadi Kesalahan"]
        //     ], 500);
        // }

    }
}
