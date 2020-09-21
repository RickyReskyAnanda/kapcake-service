<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Http\Requests\Api\BiayaTambahanRequest;
use App\Http\Resources\BiayaTambahan as BiayaTambahanResource;
use App\BiayaTambahan;

class BiayaTambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', BiayaTambahan::class);

        try {   
            if(isset($request->paginate) && $request->paginate == 'true')
                $data = $request->user()->bisnis
                        ->biayaTambahan()
                        ->where(function($q) use ($request){
                            if($request->has('outlet_id'))
                                $q->where('outlet_id', $request->outlet_id);
                            if($request->has('pencarian'))
                                $q->where('nama_biaya_tambahan', 'like', '%'.$request->pencarian.'%');
                        })->paginate();
            else
                $data = $request->user()->bisnis
                        ->biayaTambahan()
                        ->where(function($q) use ($request){
                            if($request->has('outlet_id'))
                                $q->where('outlet_id', $request->outlet_id);
                        })
                        ->get();

            return BiayaTambahanResource::collection($data);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BiayaTambahanRequest $request)
    {
        
        $this->authorize('create', BiayaTambahan::class);

        $data = $request->validated();
        DB::beginTransaction();
        try {   
                $tersedia = $request->user()->bisnis
                ->biayaTambahan()
                ->where(function($q) use ($data) {
                    $q->where('outlet_id',$data['outlet_id']);
                    $q->where('nama_biaya_tambahan', $data['nama_biaya_tambahan']);
                })->count();
                
            if($tersedia == 0)
                $biayaTambahan = $request->user()
                            ->bisnis
                            ->biayaTambahan()
                            ->create($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Biaya Tambahan tidak boleh sama"]
                ], 422);
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Biaya Tambahan berhasil ditambahkan"]
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
    public function show(BiayaTambahan $biayaTambahan)
    {
        $this->authorize('show', $biayaTambahan);

        return new BiayaTambahanResource($biayaTambahan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BiayaTambahanRequest $request, BiayaTambahan $biayaTambahan)
    {
        $this->authorize('update', $biayaTambahan);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->biayaTambahan()
            ->where(function($q) use ($data, $biayaTambahan) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_biaya_tambahan', $data['nama_biaya_tambahan']);
                $q->where('id_biaya_tambahan','!=', $biayaTambahan->id_biaya_tambahan);
            })->count();
            if($tersedia == 0)
                $biayaTambahan->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Biaya Tambahan tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Biaya Tambahan berhasil diperbaharui"]
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
    public function destroy(BiayaTambahan $biayaTambahan)
    {
        $this->authorize('delete', $biayaTambahan);
        
        DB::beginTransaction();
        try {
            $biayaTambahan->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Biaya Tambahan berhasil dihapus"]
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
