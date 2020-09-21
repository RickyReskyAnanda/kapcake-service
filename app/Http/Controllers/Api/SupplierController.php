<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Supplier;
use App\Http\Requests\Api\SupplierRequest;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Supplier::class);
        try {   
        if(isset($request->paginate) && $request->paginate == 'true')
            return $request->user()->bisnis
                    ->supplier()
                    ->where(function($q){
                        if(isset(request()->pencarian)){
                            $q->where('nama', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('alamat', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('nomor_telpon', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('email', 'like', '%'.request()->pencarian.'%');
                        }
                    })
                    ->paginate();
        else
            return $request->user()->bisnis
                    ->supplier()
                    ->get();
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(SupplierRequest $request)
    {
        $this->authorize('create', Supplier::class);
        
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $tersedia = $request->user()->bisnis
            ->supplier()
            ->where(function($q) use ($data) {
                $q->where(function($q) use ($data){
                    $q->where('email', $data['email']);
                    $q->orWhere('nomor_telpon', $data['nomor_telpon']);
                });
                $q->where('nama', $data['nama']);
            })->count();

            if($tersedia == 0)
                $request->user()->bisnis
                    ->supplier()
                    ->create($data);
             else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Supplier tidak boleh sama"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Supplier berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('show', $supplier);

        return $supplier;
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);
     
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $tersedia = $request->user()->bisnis
            ->supplier()
            ->where(function($q) use ($data, $supplier) {
                $q->where(function($q) use ($data){
                    $q->where('email', $data['email']);
                    $q->orWhere('nomor_telpon', $data['nomor_telpon']);
                });
                $q->where('nama', $data['nama']);
                $q->where('id_supplier', '!=', $supplier->id_supplier);
            })->count();

            if($tersedia == 0)
                $supplier->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Supplier tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Supplier berhasil diperbaharui"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);

        DB::beginTransaction();
        try {
            $supplier->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Supplier berhasil dihapus"]
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
