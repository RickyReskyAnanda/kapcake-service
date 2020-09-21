<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\KategoriMeja;
use App\Http\Requests\Api\KategoriMejaRequest;
use App\Http\Resources\KategoriMejaIndex as KategoriMejaIndexResource;
use App\Http\Resources\KategoriMejaShow as KategoriMejaShowResource;

class KategoriMejaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriMeja::class);

        try {   
            if(isset($request->paginate) && $request->paginate == 'true')
                $data =  $request->user()->bisnis
                        ->kategoriMeja()
                        ->where(function($q) use ($request){
                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('outlet_id', $request->outlet_id);
                            if($request->has('pencarian'))
                                $q->where('nama_kategori_meja', request()->pencarian);
                        })
                        ->paginate();
            else
                $data = $request->user()
                        ->bisnis
                        ->kategoriMeja()
                        ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                        ->where('is_aktif', 1)
                        ->get();
            return KategoriMejaIndexResource::collection($data);

        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(KategoriMejaRequest $request)
    {
        $this->authorize('create', KategoriMeja::class);

        $data = $request->validated();
        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
                ->kategoriMeja()
                ->where(function($q) use ($data) {
                    $q->where('outlet_id',$data['outlet_id']);
                    $q->where('nama_kategori_meja', $data['nama_kategori_meja']);
                })->count();

            if($tersedia == 0)
                $request->user()->bisnis
                            ->kategoriMeja()
                            ->create($request->all());
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Nama Kategori Meja tidak boleh sama"]
                ], 422);
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Meja berhasil ditambahkan"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(KategoriMeja $kategoriMeja)
    {
        $this->authorize('show',$kategoriMeja);

        return new KategoriMejaShowResource($kategoriMeja);
    }

    public function update(KategoriMejaRequest $request, KategoriMeja $kategoriMeja)
    {
        $this->authorize('update', $kategoriMeja);

        $data = $request->validated();

        DB::beginTransaction();
        try {
             $tersedia = $request->user()->bisnis
                ->kategoriMeja()
                ->where(function($q) use ($data,$kategoriMeja) {
                    $q->where('outlet_id',$data['outlet_id']);
                    $q->where('nama_kategori_meja', $data['nama_kategori_meja']);
                    $q->where('id_kategori_meja', '!=', $kategoriMeja->id_kategori_meja);
                })->count();

            if($tersedia == 0)
                $kategoriMeja->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Nama Kategori Meja tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Meja berhasil diperbaharui"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(KategoriMeja $kategoriMeja)
    {
        $this->authorize('delete', $kategoriMeja);

        DB::beginTransaction();
        try {
            $kategoriMeja->delete();
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Meja berhasil dihapus"]
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
