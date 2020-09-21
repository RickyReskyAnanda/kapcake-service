<?php

namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\KategoriMeja;
use App\Meja;
use App\Http\Requests\Api\MejaRequest;
use App\Http\Resources\MejaIndex as MejaIndexResource;
use App\Http\Resources\MejaShow as MejaShowResource;

class MejaController extends Controller
{
    public function index(Request $request)
    {
        // $this->authorize('view', Meja::class);
        try {

            if(isset($request->paginate) && $request->paginate == 'true')
                $data =  $request->user()->bisnis
                        ->meja()
                        ->where(function($q) use ($request){
                            $q->where('outlet_id', $request->outlet_id);

                            if($request->has('kategori_meja_id') && $request->kategori_meja_id != 0)
                                $q->where('kategori_meja_id', $request->kategori_meja_id);
                            
                            if(isset($request->pencarian))
                                $q->where('nama_meja', 'like', '%'.$request->pencarian.'%');
                        })
                        ->paginate(10);
            else
                $data = $request->user()->bisnis
                        ->meja()
                        ->where(function($q) use ($request){
                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('outlet_id', $request->outlet_id);

                            $q->where('nama_meja', 'like', '%'.$request->pencarian.'%');
                        })
                        ->get();
            return MejaIndexResource::collection($data);

        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(MejaRequest $request)
    {
        // $this->authorize('create', Meja::class);

        $data = $request->validated();
        DB::beginTransaction();
        try {
            if($request->has('outlet_id') && $request->outlet_id > 0){
                $request->user()
                            ->bisnis
                            ->meja()
                            ->create($data);
                DB::commit();
                return response([
                'status' => 'success',
                'message' =>  ["Meja berhasil ditambahkan"]
            ], 200);
            }else{
                DB::rollback();
                return response([
                    'status' => 'error',
                    'message' =>  ["Terjadi Kesalahan"]
                ], 500);
            }
        } catch (\Exception $e){
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        } 
    }

    public function show(Meja $meja)
    {
        // $this->authorize('show', $meja);
        return new MejaShowResource($meja);
    }

    public function update(MejaRequest $request, Meja $meja)
    {
        // $this->authorize('update', $meja);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $meja->update($data);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Meja berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(Meja $meja)
    {
        // $this->authorize('delete', $meja);
        DB::beginTransaction();
        try {
            $meja->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Meja berhasil ditambahkan"]
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
