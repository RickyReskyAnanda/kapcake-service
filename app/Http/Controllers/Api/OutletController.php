<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Outlet;
use App\Http\Resources\OutletPaket as OutletPaketResource;
use App\Http\Resources\OutletIndex as OutletIndexResource;
use App\Http\Resources\UserLogin as UserLoginResource;

use App\Http\Requests\Api\OutletRequest;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Outlet::class);

        // try {   
            // $data = [];
            if($request->is_outlet_package == 1){
                $data = $request->user()->bisnis
                        ->outlet()
                        // ->with('aktivasi')
                        ->paginate();
          
                return OutletPaketResource::collection($data);
            }
            elseif(isset($request->paginate) && $request->paginate == true){
                $data = $request->user()->bisnis
                        ->outlet()
                        ->with('pajakTerpilih')
                        ->where(function($q){
                            if(isset(request()->pencarian)){
                                $q->where('nama_outlet', 'like', '%'.request()->pencarian.'%');
                                $q->orWhere('alamat', 'like', '%'.request()->pencarian.'%');
                                $q->orWhere('kode_pos', 'like', '%'.request()->pencarian.'%');
                                $q->orWhere('email', 'like', '%'.request()->pencarian.'%');
                                $q->orWhereHas('pajak', function($q){
                                    $q->where('nama_pajak','like', '%'.request()->pencarian.'%');
                                });
                            }
                        })
                        ->paginate();
                return OutletIndexResource::collection($data);
                        
                        
            }else
                return $request->user()->bisnis
                        ->outlet()
                        ->get();

            // return response([
            //     'data' => ,
            //     'message' => 'Berhasil mengambil data'
            // ]);

        // } catch (\Exception $e) {
        //     return response([
        //         'status' => 'error',
        //         'message' =>  ["Terjadi Kesalahan"]
        //     ], 500);
        // }
    }

    public function store(OutletRequest $request)
    {
        $this->authorize('create', Outlet::class);
        
        $data = $request->validated();
        DB::beginTransaction();
        try {   
            $outlet = $request->user()->bisnis
                            ->outlet()
                            ->create($data);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Outlet berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(Outlet $outlet)
    {
        $this->authorize('show', $outlet);

        return $outlet;
    }

    public function update(OutletRequest $request, Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $outlet->update($data);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Outlet berhasil diperbaharui"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(Outlet $outlet)
    {
        $this->authorize('delete', $outlet);
        
        DB::beginTransaction();
        try {
            $outlet->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Outlet berhasil dihapus"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }
}
