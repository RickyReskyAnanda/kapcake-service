<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\KategoriMenu;
use App\Http\Requests\Api\KategoriMenuRequest;
use App\Http\Resources\KategoriMenu as KategoriMenuResource;

class KategoriMenuController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriMenu::class);

        try {   
            if(isset($request->paginate) && $request->paginate == 'true')
                $data =  $request->user()
                    ->bisnis
                    ->kategoriMenu()
                    ->where(function($q) use ($request){
                        $q->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '0');
                        if($request->has('pencarian'))
                            $q->where('nama_kategori_menu','like', '%'.$request->pencarian.'%');
                    })
                    ->orderBy('is_paten','desc')
                    ->orderBy('nama_kategori_menu','asc')
                    ->paginate(10);
            else
                $data =  $request->user()
                    ->bisnis
                    ->kategoriMenu()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                    ->orderBy('is_paten','desc')
                    ->orderBy('nama_kategori_menu','asc')
                    ->get();

            return KategoriMenuResource::collection($data);

        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store(KategoriMenuRequest $request)
    {
        $this->authorize('create', KategoriMenu::class);
        
        $data = $request->validated();
        DB::beginTransaction();
        try {  
            $tersedia = $request->user()->bisnis
            ->kategoriMenu()
            ->where(function($q) use ($data) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_kategori_menu', $data['nama_kategori_menu']);
            })->count();

            if($tersedia == 0)
                $request->user()->bisnis
                            ->kategoriMenu()
                            ->create($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Kategori Menu tidak boleh sama"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Kategori Menu berhasil dihapus"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function show(KategoriMenu $kategoriMenu)
    {
        $this->authorize('show', $kategoriMenu);
        
        return $kategoriMenu;
    }

    public function update(KategoriMenuRequest $request, KategoriMenu $kategoriMenu)
    {
        $this->authorize('update', $kategoriMenu);

        $data = $request->validated();

        DB::beginTransaction();
        try {
            $tersedia = $request->user()->bisnis
            ->kategoriMenu()
            ->where(function($q) use ($data, $kategoriMenu) {
                $q->where('outlet_id',$data['outlet_id']);
                $q->where('nama_kategori_menu', $data['nama_kategori_menu']);
                $q->where('id_kategori_menu', '!=',$kategoriMenu->id_kategori_menu);
            })->count();

            if($tersedia == 0)
                $kategoriMenu ->update($data);
            else
                return response([
                    'status' => 'warning',
                    'message' =>  ["Kategori Menu tidak boleh duplikat"]
                ], 422);

            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Kategori Menu berhasil dihapus"]
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'status' => 'error',
                'message' =>  ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function destroy(KategoriMenu $kategoriMenu)
    {
        $this->authorize('delete', $kategoriMenu);

        DB::beginTransaction();
        try {
            $kategoriMenu->delete();
            DB::commit();
            return response([
                'status' => 'success',
                'message' =>  ["Kategori Menu berhasil dihapus"]
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
