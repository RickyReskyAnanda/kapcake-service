<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Http\Requests\Api\DiskonRequest;
use App\Http\Resources\DiskonShow as DiskonShowResource;
use App\Http\Resources\DiskonIndex as DiskonIndexResource;
use App\Diskon;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('view', Diskon::class);

        try {   

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->diskon()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '')
                    ->where(function($q) use ($request){
                        $q->where('nama_diskon', 'like', '%'.$request->pencarian.'%');
                        $q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
                    })
                    ->latest()
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->diskon()
                    ->where('nama_diskon', $request->has('outlet_id') ? $request->outlet_id : '0' )
                    ->orderBy('nama_diskon','asc')
                    ->get();

        return DiskonIndexResource::collection($data);

        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(DiskonRequest $request)
    {
        // $this->authorize('create', Diskon::class);
        
        $data = $request->validated();
        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->diskon()
            ->where(function($q) use ($data) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_diskon', $data['nama_diskon']);
            })->count();
                
            if($tersedia == 0)
                $request->user()->bisnis
                            ->diskon()
                            ->create($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Diskon tidak boleh sama"]
                ], 422);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Diskon berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Diskon $diskon)
    {
        // $this->authorize('show', $diskon);
        return new DiskonShowResource($diskon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DiskonRequest $request, Diskon $diskon)
    {
        // $this->authorize('update', $diskon);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->diskon()
            ->where(function($q) use ($data, $diskon) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_diskon', $data['nama_diskon']);
                $q->where('id_diskon','!=', $diskon->id_diskon);
            })->count();
            if($tersedia == 0)
                $diskon ->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Diskon tidak boleh duplikat"]
                ], 422);
            
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Diskon berhasil diperbaharui"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diskon $diskon)
    {
        // $this->authorize('delete', $diskon);

        DB::beginTransaction();
        try {
            $diskon->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Diskon berhasil dihapus"]
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
