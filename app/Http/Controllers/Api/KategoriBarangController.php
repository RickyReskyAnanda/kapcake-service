<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\KategoriBarang;
use App\Http\Requests\Api\KategoriBarangRequest;
use App\Http\Resources\KategoriBarang as KategoriBarangResource;

class KategoriBarangController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriBarang::class);
        
        try {   
            if(request()->has('paginate') && $request->paginate == 'true'){
                $data = $request->user()->bisnis
                        ->kategoriBarang()
                        ->with('outlet')
                        ->where(function($q) use ($request){
                            if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
                                $q->where('outlet_id', $request->outlet_id);
                            if($request->has('pencarian'))
                                $q->where('nama_kategori_barang','like', '%'.request()->pencarian.'%');
                        })
                        ->orderBy('is_paten','desc')
                        ->orderBy('id_kategori_barang','desc')
                        ->paginate();
            }
            else
                $data = $request->user()->bisnis
                        ->kategoriBarang()
                        ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                        ->orderBy('is_paten','desc')
                        ->orderBy('nama_kategori_barang','asc')
                        ->get();

            return KategoriBarangResource::collection($data);

        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(KategoriBarangRequest $request)
    {
        $this->authorize('create', KategoriBarang::class);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
                            ->kategoriBarang()
                            ->where(function($q) use ($data) {
                                $q->where('outlet_id', $data['outlet_id']);
                                $q->where('nama_kategori_barang', $data['nama_kategori_barang']);
                            })->count();

            if($tersedia == 0)
                $request->user()->bisnis
                            ->kategoriBarang()
                            ->create($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Nama Kategori Barang tidak boleh sama"]
                ], 422);
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Barang berhasil ditambahkan"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(KategoriBarang $kategoriBarang)
    {
        $this->authorize('show', $kategoriBarang);

        return $kategoriBarang;
    }

    public function update(KategoriBarangRequest $request, KategoriBarang $kategoriBarang)
    {
        $this->authorize('update', $kategoriBarang);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
                            ->kategoriBarang()
                            ->where(function($q) use ($data,$kategoriBarang) {
                                $q->where('outlet_id', $data['outlet_id']);
                                $q->where('nama_kategori_barang', $data['nama_kategori_barang']);
                                $q->where('id_kategori_barang', '!=', $kategoriBarang->id_kategori_barang);
                            })->count();
            if($tersedia == 0)
                $kategoriBarang->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Nama Kategori Barang tidak boleh duplikat"]
                ], 422);
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Barang berhasil diperbaharui"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(KategoriBarang $kategoriBarang)
    {
        $this->authorize('delete', $kategoriBarang);

        DB::beginTransaction();
        try {
            $kategoriBarang->delete();
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Barang berhasil dihapus"]
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
