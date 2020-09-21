<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\KategoriBahanDapur;
use App\Http\Requests\Api\KategoriBahanDapurRequest;
use App\Http\Resources\KategoriBahanDapur as KategoriBahanDapurResource;
use App\Http\Resources\KategoriBahanDapurShow as KategoriBahanDapurShowResource;

class KategoriBahanDapurController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriBahanDapur::class);
        
        try {   
            if($request->has('paginate') && $request->paginate == 'true')
                $data =  $request->user()->bisnis
                        ->kategoriBahanDapur()
                        ->with('outlet')
                        ->where(function($q) use ($request){
                            if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
                                $q->where('outlet_id', $request->outlet_id);
                            if($request->has('pencarian'))
                                $q->where('nama_kategori_bahan_dapur','like', '%'.$request->pencarian.'%');
                        })
                        ->orderBy('is_paten','desc')
                        ->orderBy('id_kategori_bahan_dapur','desc')
                        ->paginate();
            else
                $data =  $request->user()->bisnis
                        ->kategoriBahanDapur()
                        ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                        ->orderBy('is_paten','desc')
                        ->orderBy('nama_kategori_bahan_dapur','asc')
                        ->get();

            return KategoriBahanDapurResource::collection($data);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(KategoriBahanDapurRequest $request)
    {
        $this->authorize('create', KategoriBahanDapur::class);
        
        $data = $request->validated();
        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->kategoriBahanDapur()
            ->where(function($q) use ($data) {
                $q->where('outlet_id', $data['outlet_id']);
                $q->where('nama_kategori_bahan_dapur', $data['nama_kategori_bahan_dapur']);
            })->count();

            if($tersedia == 0)
                $request->user()->bisnis
                            ->kategoriBahanDapur()
                            ->create($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Nama Kategori Bahan Dapur tidak boleh sama"]
                ], 422);
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Bahan Dapur berhasil ditambahkan"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(KategoriBahanDapur $kategoriBahanDapur)
    {
        $this->authorize('show', $kategoriBahanDapur);
        
        return new KategoriBahanDapurShowResource($kategoriBahanDapur);
    }

    public function update(KategoriBahanDapurRequest $request, KategoriBahanDapur $kategoriBahanDapur)
    {
        $this->authorize('update', $kategoriBahanDapur);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->kategoriBahanDapur()
            ->where(function($q) use ($data, $kategoriBahanDapur) {
                $q->where('outlet_id', $data['outlet_id']);
                $q->where('nama_kategori_bahan_dapur', $data['nama_kategori_bahan_dapur']);
                $q->where('id_kategori_bahan_dapur','!=', $kategoriBahanDapur->id_kategori_bahan_dapur);
            })->count();

            if($tersedia == 0)
                $kategoriBahanDapur->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Nama Kategori Bahan Dapur tidak boleh duplikat"]
                ], 422);
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Bahan Dapur berhasil diperbaharui"]
                ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(KategoriBahanDapur $kategoriBahanDapur)
    {
        $this->authorize('delete', $kategoriBahanDapur);

        DB::beginTransaction();
        try {
            $kategoriBahanDapur->delete();
            DB::commit();
            return response([
                    'status' => 'success',
                    'message' =>  ["Kategori Bahan Dapur berhasil dihapus"]
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
