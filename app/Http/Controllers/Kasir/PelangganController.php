<?php

namespace App\Http\Controllers\Kasir;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Outlet;
use App\Http\Resources\Kasir\Pelanggan as PelangganResource;

class PelangganController extends Controller
{
    public function index(Request $request){
        $pelanggan = $request->user()->bisnis
                                ->pelanggan()
                                ->where(function($q) use ($request){
                                    if($request->has('pencarian') && $request->pencarian != ''){
                                        $q->where('nama_pelanggan', 'like','%'.$request->pencarian.'%');
                                        $q->orWhere('email', 'like','%'.$request->pencarian.'%');
                                        $q->orWhere('no_hp', 'like','%'.$request->pencarian.'%');
                                    }
                                })
                                ->skip($request->nomor_urut * 35)
                                ->limit(35)
                                ->latest()
                                ->get();

        return response()->json([
            'status' => 'success',
            'data' => PelangganResource::collection($pelanggan),
            'total' => count($pelanggan),
            'message' => ["Berhasil mengambil data"]
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
                $request
                    ->user()
                    ->bisnis
                    ->pelanggan()
                    ->updateOrCreate(
                        [   
                            'unique_id' => $data['unique_id'],
                        ],
                        [
                            'unique_id' => $data['unique_id'],
                            'email' => $data['email'],
                            'nama_pelanggan' => $data['nama_pelanggan'],
                            'no_hp' => $data['no_hp'],
                            'jk' => $data['jk'],
                        ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => ["Berhasil meyimpan data pelanggan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'unique_id' => 'required',
            'nama_pelanggan' =>  'required',
            'email' =>  'nullable',
            'no_hp' => 'nullable',
            'jk' => 'nullable'
        ];
    }
}
