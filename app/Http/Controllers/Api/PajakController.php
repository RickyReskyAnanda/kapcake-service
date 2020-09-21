<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Http\Requests\Api\PajakRequest;
use App\Http\Resources\Pajak as PajakResource;
use App\Pajak;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Pajak::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->pajak()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '')
                    ->where(function($q) use ($request){
                        $q->where('nama_pajak', 'like', '%'.$request->pencarian.'%');
                        $q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
                    })
                    ->latest()
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->pajak()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '0' )
                    ->orderBy('nama_pajak','asc')
                    ->get();

        return PajakResource::collection($data);
    }

    public function store(PajakRequest $request)
    {
        $this->authorize('create', Pajak::class);
        
        $data = $request->validated();
        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->pajak()
            ->where(function($q) use ($data) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_pajak', $data['nama_pajak']);
            })->count();
            
            if($tersedia == 0)
                $request->user()->bisnis
                            ->pajak()
                            ->create($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Pajak tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Pajak berhasil ditambahkan"]
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
    public function show(Pajak $pajak)
    {
        $this->authorize('show', $pajak);
        return new PajakResource($pajak);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PajakRequest $request, Pajak $pajak)
    {
        $this->authorize('update', $pajak);

        $data = $request->validated();

        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->pajak()
            ->where(function($q) use ($data, $pajak) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_pajak', $data['nama_pajak']);
                $q->where('id_pajak','!=', $pajak->id_pajak);
            })->count();
            
            if($tersedia == 0)
                $pajak->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Pajak tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Pajak berhasil ditambahkan"]
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
    public function destroy(Pajak $pajak)
    {
        $this->authorize('delete', $pajak);

        DB::beginTransaction();
        try {
            $pajak->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Pajak berhasil ditambahkan"]
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
