<?php

namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TipePenjualan;
use App\Http\Requests\Api\TipePenjualanRequest;
use App\Http\Resources\TipePenjualanIndex as TipePenjualanIndexResource;
use App\Http\Resources\TipePenjualanShow as TipePenjualanShowResource;

class TipePenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', TipePenjualan::class);
        try {   
            if(isset($request->paginate) && $request->paginate == 'true')
                $data = $request->user()->bisnis
                        ->tipePenjualan()
                        ->where(function($q) use ($request){
                            $q->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0);
                            if($request->has('pencarian'))
                                $q->where('nama_tipe_penjualan', 'like', '%'.$request->pencarian.'%');
                        })
                        ->latest()
                        ->paginate();
            else
                $data = $request->user()->bisnis
                        ->tipePenjualan()
                        ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)
                        ->orderBy('nama_tipe_penjualan','asc')
                        ->get();

            return TipePenjualanIndexResource::collection($data);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(TipePenjualanRequest $request)
    {
        $this->authorize('create', TipePenjualan::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $tersedia = $request->user()->bisnis
            ->tipePenjualan()
            ->where(function($q) use ($data) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_tipe_penjualan', $data['nama_tipe_penjualan']);
            })->count();

            if($tersedia == 0){
                $tipePenjualan = $request->user()->bisnis
                                ->tipePenjualan()
                                ->create([
                                    'nama_tipe_penjualan' => $data['nama_tipe_penjualan'],
                                    'is_aktif' => $data['is_aktif'],
                                    'outlet_id' => $data['outlet_id'],
                                ]);
                
                foreach ($data['biaya_tambahan'] as $d) {
                    $tipePenjualan
                        ->biayaTambahan()
                        ->create($d);
                }
            }else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Jenis Pemesanan tidak boleh sama"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Jenis Pemesanan berhasil ditambahkan"]
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
    public function show(TipePenjualan $tipePenjualan)
    {
        $this->authorize('show', $tipePenjualan);
        $tipePenjualan->load('biayaTambahan');
        return new TipePenjualanShowResource($tipePenjualan);
    }

    public function update(TipePenjualanRequest $request, TipePenjualan $tipePenjualan)
    {
        $this->authorize('update', $tipePenjualan);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {
             $tersedia = $request->user()->bisnis
            ->tipePenjualan()
            ->where(function($q) use ($data, $tipePenjualan) {
                $q->where('outlet_id', $data['outlet_id']);
                $q->where('nama_tipe_penjualan', $data['nama_tipe_penjualan']);
                $q->where('id_tipe_penjualan','!=', $tipePenjualan->id_tipe_penjualan);
            })->count();
            if($tersedia == 0){
                $tipePenjualan
                    ->update([
                        'nama_tipe_penjualan' => $data['nama_tipe_penjualan'],
                        'is_aktif' => $data['is_aktif'],
                    ]);
                $idTipePenjualanBiayaTambahan = [];
                foreach ($data['biaya_tambahan'] as $d) {
                    $opsi = $tipePenjualan
                        ->biayaTambahan()
                        ->updateOrCreate(['biaya_tambahan_id' => $d['id'], 'outlet_id' => $tipePenjualan->outlet_id]);
                    array_push($idTipePenjualanBiayaTambahan, $opsi['id_tipe_penjualan_biaya_tambahan']); 
                }
                $tipePenjualan
                    ->biayaTambahan()
                    ->whereNotIn('id_tipe_penjualan_biaya_tambahan', $idTipePenjualanBiayaTambahan)
                    ->delete();
            }else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Jenis Pemesanan tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Jenis Pemesanan berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(TipePenjualan $tipePenjualan)
    {
        $this->authorize('delete', $tipePenjualan);

        DB::beginTransaction();
        try {
            $tipePenjualan->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Jenis Pemesanan berhasil ditambahkan"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function validation(){
        return [
            'nama_tipe_penjualan' => 'required|max:50',
            'is_aktif' => 'required|max:1|min:0',
            'biaya_tambahan' => 'nullable',
            'outlet_id' => 'nullable',
        ];
    }
}
