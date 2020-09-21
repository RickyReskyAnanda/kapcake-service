<?php

namespace App\Http\Controllers\Api;

use DB;
use App\BahanDapur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\BahanDapurRequest;

use App\Image\BlobImageConvertion;
use App\Http\Resources\BahanDapur as BahanDapurResource;

class BahanDapurController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', BahanDapur::class);

        try {   
            if(isset($request->paginate) && $request->paginate == 'true')
                $data = $request->user()->bisnis
                    ->bahanDapur()
                    ->with('kategori', 'satuan')
                    ->where(function($q) use ($request){
                        $q->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '0');
                        $q->where('kategori_bahan_dapur_id', $request->kategori_bahan_dapur_id > 0 ? '=' :'!=' , $request->kategori_bahan_dapur_id );
                        $q->whereHas('kategori', function($q){
                            $q->where('nama_kategori_bahan_dapur', 'like', '%'.request()->pencarian.'%');
                        });
                        $q->orWhereHas('satuan', function($q){
                            $q->where('satuan', 'like', '%'.request()->pencarian.'%');
                        });
                        $q->orWhere('nama_bahan_dapur', 'like', '%'.request()->pencarian.'%');
                    })
                    ->paginate();
      
            return BahanDapurResource::collection($data);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(BahanDapurRequest $request)
    {
        $this->authorize('create', BahanDapur::class);

        $data = $request->validated();
        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->bahanDapur()
            ->where(function($q) use ($data) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('satuan_id',$data['satuan_id']);
                $q->where('kategori_bahan_dapur_id',$data['kategori_bahan_dapur_id']);
                $q->where('nama_bahan_dapur', $data['nama_bahan_dapur']);
            })->count();

            if($tersedia == 0)
                $request->user()->bisnis
                    ->bahanDapur()
                    ->create($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Bahan Dapur tidak boleh sama"]
                ], 422);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Bahan Dapur berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(BahanDapur $bahanDapur)
    {
        $this->authorize('view', BahanDapur::class);

        return $bahanDapur;
    }

    public function update(BahanDapurRequest $request, BahanDapur $bahanDapur)
    {
        $this->authorize('update', $bahanDapur);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->bahanDapur()
            ->where(function($q) use ($data, $bahanDapur) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('satuan_id',$data['satuan_id']);
                $q->where('kategori_bahan_dapur_id', $data['kategori_bahan_dapur_id']);
                $q->where('nama_bahan_dapur', $data['nama_bahan_dapur']);

                $q->where('id_bahan_dapur','!=', $bahanDapur->id_bahan_dapur);
            })->count();

            if($tersedia == 0)
                $bahanDapur->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Bahan Dapur tidak boleh duplikat"]
                ], 422);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Bahan Dapur berhasil diperbaharui"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(BahanDapur $bahanDapur)
    {
        $this->authorize('delete', $bahanDapur);

        DB::beginTransaction();
        try {
            $bahanDapur->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Bahan Dapur berhasil dihapus"]
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
