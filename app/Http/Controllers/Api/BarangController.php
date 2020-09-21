<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Barang;
use App\Http\Requests\Api\BarangRequest;
use App\Http\Resources\Barang as BarangResource;

class BarangController extends Controller
{
    public function index(Request $request)
    {   
        $this->authorize('view', Barang::class);
        try {   
        
            if(isset($request->paginate) && $request->paginate == 'true')
                $data = $request->user()->bisnis
                    ->barang()
                    ->with('kategori', 'satuan','outlet')
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
                            $q->where('outlet_id', $request->outlet_id);

                        if($request->has('kategori_barang_id') && $request->kategori_barang_id != '' && $request->kategori_barang_id != 0)
                            $q->where('kategori_barang_id', $request->kategori_barang_id);

                        if($request->has('pencarian')){
                            $q->where(function($q) use ($request){
                                $q->whereHas('kategori', function($q) use ($request){
                                    $q->where('nama_kategori_barang', 'like', '%'.$request->pencarian.'%');
                                });
                                $q->orWhereHas('satuan', function($q) use ($request){
                                    $q->where('satuan', 'like', '%'.$request->pencarian.'%');
                                });
                                $q->orWhere('nama_barang', 'like', '%'.$request->pencarian.'%');
                            });
                        }
                    })
                    ->orderBy('created_at','desc')
                    ->paginate();
            return BarangResource::collection($data);

        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(BarangRequest $request)
    {
        $this->authorize('create', Barang::class);

        $data = $request->validated();
        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
                ->barang()
                ->where(function($q) use ($data) {
                    $q->where('outlet_id',$data['outlet_id']);
                    $q->where('satuan_id',$data['satuan_id']);
                    $q->where('kategori_barang_id',$data['kategori_barang_id']);
                    $q->where('nama_barang', $data['nama_barang']);
                })->count();

                if($tersedia == 0)
                    $barang = $request->user()->bisnis
                                ->barang()
                                ->create($data);
                else
                    return response([
                        'status' => 'warning',
                        'message' =>  ["Barang tidak boleh sama"]
                    ], 422);
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Barang berhasil ditambahkan"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(Barang $barang)
    {
        $this->authorize('show', $barang);
        return $barang;
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $this->authorize('update', $barang);

        $data = $request->validated();

        DB::beginTransaction();
        try {
            $tersedia = $request->user()->bisnis
                ->barang()
                ->where(function($q) use ($data, $barang) {
                    $q->where('outlet_id', $data['outlet_id']);
                    $q->where('nama_barang', $data['nama_barang']);
                    $q->where('satuan_id', $data['satuan_id']);
                    $q->where('kategori_barang_id', $data['kategori_barang_id']);
                    $q->where('id_barang', '!=', $barang->id_barang);
                })->count();

            if($tersedia == 0)
                $barang->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Barang tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Barang berhasil diperbaharui"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(Barang $barang)
    {
        $this->authorize('delete', $barang);

        DB::beginTransaction();
        try {
            $barang->delete();
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Barang berhasil dihapus"]
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
