<?php

namespace App\Http\Controllers\Kasir;

use DB;
use App\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Kasir\Menu as MenuResource;
use App\Http\Resources\Kasir\Printer as PrinterResource;
use App\Http\Resources\Kasir\Pesanan as PesananResource;
use App\Http\Resources\Kasir\Penjualan as PenjualanResource;
use App\Http\Resources\Kasir\Pelanggan as PelangganResource;
use App\Http\Resources\Bisnis as BisnisResource;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->validate([
            'outlet_id' => 'required',
            'perangkat_id' => 'required',
        ]);

        $data = $request->user()->bisnis
                ->outlet()
                ->where('id_outlet', $request->outlet_id)
        ->first();
    
        $data->load([
                    
                    'kategoriMenu' => function($q){
                        $q->has('menu');
                        $q->orderBy('is_paten','asc');
                        $q->orderBy('nama_kategori_menu','asc');
                    }, 
                    'meja', 
                    'kategoriMeja' => function($q){
                        $q->has('meja');
                    },
                    'pajakTerpilih', 
                    'diskon',
                    'biayaTambahan',
                    'jenisPemesanan'  => function($q){
                        $q->where('is_aktif',1);
                    }, 
                    'pemesanan' => function($q){
                        $q->with('item.menu.gambar');
                        $q->orderBy('tanggal_simpan','desc');
                        $q->orderBy('waktu_simpan','desc');
                        $q->offset(0);
                        $q->limit(15);
                    },
                    'penjualan' => function($q){
                        $q->with('item.menu.gambar');
                        $q->orderBy('tanggal_proses','desc');
                        $q->orderBy('waktu_proses','desc');
                        $q->offset(0);
                        $q->limit(15);
                    }]
                );
        if(isset($data->pemesanan))
        $noUrutPesanan = $data
                    ->pemesanan
                    ->where('tanggal_simpan', date('Y-m-d'))  /// tanggalnya belum bisa disesuaikan dgn perangkat 
                    ->max('no_pemesanan');
        $perangkat = \App\Perangkat::find($request->perangkat_id);
        $printer = $request
                    ->user()
                    ->bisnis
                    ->printer()
                    ->where('user_id', $request->user()->id)
                    ->where('perangkat_id',$perangkat->id_perangkat)
                    ->get();

        $outletTerpilih = $request->user()->bisnis->outlet()->where('id_outlet', $request->outlet_id)->first();
        $bisnis = $request->user()->bisnis;
        $bisnis->load('thumbLogo');

        $outletTerpilih['url_logo'] = $bisnis->thumbLogo->link ?? '';

        $pelanggan = $request->user()->bisnis->pelanggan ?? null;

        return response()->json([
            'jenis_pemesanan' => $data->jenisPemesanan ?? null,
            'kategori_menu' => $data->kategoriMenu ?? null,
            'kategori_meja' => $data->kategoriMeja ?? null,
            'meja' => $data->meja ?? null,
            'pelanggan' => PelangganResource::collection($pelanggan),
            'pajak' => $data->pajakTerpilih ?? null,
            'diskon' => $data->diskon ?? null,
            'biaya_tambahan' => $data->biayaTambahan ?? null,
            'pemesanan' => $data->pemesanan ? PesananResource::collection($data->pemesanan) : null,
            'penjualan' => $data->penjualan ? PenjualanResource::collection($data->penjualan) : null,
            'printer' => PrinterResource::collection($printer),
            'outlet' => $outletTerpilih ?? null,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Outlet::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $outlet = $request
                            ->user()
                            ->bisnis
                            ->outlet()
                            ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Outlet $outlet)
    {
        $this->authorize('show', $outlet);

        return $outlet;
    }

    public function update(Request $request, Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $outlet->update($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Outlet $outlet)
    {
        $this->authorize('delete', $outlet);
        
        DB::beginTransaction();
        try {
            $outlet->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_outlet' => 'required|max:255',
            'telpon' => 'required',
            'email' => 'required',
            'kota' => 'nullable',
            'provinsi' => 'nullable',
            'kode_pos' => 'nullable',
            'alamat' => 'nullable',
            'catatan' => 'nullable',
        ];
    }
}
