<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LaporanTransaksi as LaporanTransaksiResources;
use App\Http\Resources\LaporanPenjualan as LaporanPenjualanResources;
use App\Http\Resources\LaporanKategoriPenjualan as LaporanKategoriPenjualanCollection;
use DB;

class LaporanController extends Controller
{
    public function ringkasanPenjualan(Request $request){
    	$user = $request->user();

    	/*
		|	yang belum ada ifnya nanti dikasih if status = suksea dan seleksi pembulatannya
    	*/
    	$data = $user
			    	->bisnis
			    	->penjualan()
			    	->select(DB::raw("
			    		SUM(subtotal) as total_penjualan_kotor,  
			    		SUM(IF(status != 'sukses', total, 0)) as total_pengembalian_uang,
			    		
			    		SUM(IF(status = 'sukses',total_biaya_tambahan,0)) as total_biaya_tambahan,
			    		SUM(IF(status = 'sukses',total_pajak,0)) as total_pajak,
			    		SUM(IF(status = 'sukses',total_diskon,0)) as total_diskon,
			    		SUM(IF(status = 'sukses',total_pembulatan,0)) as total_pembulatan
			    	"))
			    	->where(function ($q) use ($request, $user){
			    		// $q->where('status','sukses');
		    		if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
				    	$q->where('.outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->whereBetween('tanggal_proses', [$request->tanggal_awal,$request->tanggal_akhir]);
			    	}
			    	})
			    	->groupBy('bisnis_id')
			    	->first();

		// $total = $user
		//     	->bisnis
		//     	->penjualanItem()
		//     	->select(DB::raw("
		//     		SUM(penjualan_item.total) as total_penjualan_kotor, 
		//     		round(SUM(penjualan_item.total_diskon) + sum((penjualan_item.total * penjualan.jumlah_diskon) / 100 )) as total_diskon, 
		//     		SUM(IF(penjualan.status != 'sukses', total, 0)) as total_pengembalian_uang,

		//     		SUM(IF(penjualan.status != 'sukses', penjualan.total_biaya_tambahan,0)) as total_biaya_tambahan,
		//     		SUM(IF(penjualan.status != 'sukses', penjualan.total_pajak,0)) as total_pajak,
		//     		SUM(penjualan.total_pembulatan) as total_pembulatan
		//     	"))
		//     	->where(function ($q) use ($request){
		//     		// $q->where('penjualan.status','sukses');
		//     		if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
		// 		    	$q->where('penjualan_item.outlet_id', $request->outlet_id);

		//     		if(	$request->has('tanggal_awal')  && 
		// 	    			$request->has('tanggal_akhir') && 
		// 	    			$request->tanggal_awal != '' && 
		// 	    			$request->tanggal_akhir != '' ){
		// 	    		$q->whereBetween('penjualan.tanggal_proses', [$request->tanggal_awal,$request->tanggal_akhir]);
		// 	    	}

		// 	    	if($request->has('pencarian') &&  $request->pencarian != ''){
		// 	    		$q->where(function($q) use ($request){
		// 				    $query->where('penjualan_item.nama_variasi_menu', 'like', '%'.$request->pencarian.'%');
		// 				    $query->orWhere('penjualan_item.nama_menu', 'like', '%'.$request->pencarian.'%');
		// 	    		});
		// 	    	}
		//     	})
		//     	->leftJoin('penjualan','penjualan.id_penjualan','penjualan_item.penjualan_id')
		//     	->first();




		$data['total_penjualan_bersih'] = (float)$data['total_penjualan_kotor'] - (float)$data['total_diskon'] - (float)$data['total_pengembalian_uang'];
		$data['total'] = ((float)$data['total_penjualan_bersih'] + 
							(float)$data['total_biaya_tambahan'] + 
							(float)$data['total_pajak'])  - 
							(float)$data['total_pembulatan'];

		return response()->json([
			'data' => $data
		]);
    }

    public function penjualan(Request $request){
    	$user = $request->user();

		/*
		| sku belum ditampilkan karena ambil dari tabel variasi_menu
		| diskon belum masuk dalam diskon item. jadi sementara dikurangi dengan diskon transaksi
		| berikan filter pencarian 
		*/    	
		/*
		|-----------------------------------------------------------
		|					Keterangan
		|-----------------------------------------------------------
		|	1. subtotal adalah hasil kali dari jumlah dan harga
		|	2. total adalah subtotal  kurang total_diskon dan total_refund
		|
		*/
		$data = $user
		    	->bisnis
		    	->penjualanItem()
		    	->with('outlet')
		    	->select(DB::raw("
	    			penjualan_item.nama_menu,
	    			penjualan_item.nama_variasi_menu,
	    			penjualan_item.outlet_id,
		    		SUM(penjualan_item.jumlah) as total_penjualan_item,
		    		SUM(penjualan_item.jumlah_refund) as total_pengembalian_item,
		    		SUM(penjualan_item.total) as total_penjualan_kotor, 
		    		round(SUM(penjualan_item.total_diskon) + (sum(penjualan_item.total) * penjualan.jumlah_diskon) / 100 ) as total_diskon,
		    		SUM(penjualan_item.total_refund) as total_pengembalian_uang,
		    		round(SUM(penjualan_item.total) - SUM(penjualan_item.total_refund) - SUM(penjualan_item.total_diskon) - sum((penjualan_item.total * penjualan.jumlah_diskon) / 100 )) as total_penjualan_bersih
		    	"))
		    	->where(function ($q) use ($request){
		    		$q->where('penjualan.status','sukses');
		    		if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
				    	$q->where('penjualan_item.outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->whereBetween('penjualan.tanggal_proses', [$request->tanggal_awal,$request->tanggal_akhir]);
			    	}

			    	if($request->has('pencarian') && $request->pencarian != ''){
			    		$q->where(function($q) use ($request){
						    $query->where('penjualan_item.nama_variasi_menu', 'like', '%'.$request->pencarian.'%');
						    $query->orWhere('penjualan_item.nama_menu', 'like', '%'.$request->pencarian.'%');
			    		});
			    	}
		    	})
		    	->leftJoin('penjualan','penjualan.id_penjualan','penjualan_item.penjualan_id')
		    	->groupBy('penjualan_item.menu_id')
		    	->groupBy('penjualan_item.variasi_menu_id')
		    	->groupBy('penjualan_item.outlet_id')
		    	->paginate(15);
		  
		$total = $user
		    	->bisnis
		    	->penjualanItem()
		    	->select(DB::raw("
		    		SUM(penjualan_item.jumlah) as total_penjualan_item,
		    		SUM(penjualan_item.jumlah_refund) as total_pengembalian_item,
		    		SUM(penjualan_item.total) as total_penjualan_kotor, 
		    		round(SUM(penjualan_item.total_diskon) + (sum(penjualan_item.total) * penjualan.jumlah_diskon) / 100 ) as total_diskon, 
		    		round(SUM(penjualan_item.total_refund)) as total_pengembalian_uang,
		    		round(SUM(penjualan_item.total) - SUM(penjualan_item.total_refund) - SUM(penjualan_item.total_diskon) - sum((penjualan_item.total * penjualan.jumlah_diskon) / 100 )) as total_penjualan_bersih 
		    	"))
		    	->where(function ($q) use ($request){
		    		if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
				    	$q->where('penjualan_item.outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->whereBetween('penjualan.tanggal_proses', [$request->tanggal_awal,$request->tanggal_akhir]);
			    	}

			    	if($request->has('pencarian') &&  $request->pencarian != ''){
			    		$q->where(function($q) use ($request){
						    $query->where('penjualan_item.nama_variasi_menu', 'like', '%'.$request->pencarian.'%');
						    $query->orWhere('penjualan_item.nama_menu', 'like', '%'.$request->pencarian.'%');
			    		});
			    	}
		    	})
		    	->leftJoin('penjualan','penjualan.id_penjualan','penjualan_item.penjualan_id')
		    	->first();
		
		return response()->json([
			'status' => 'success',
			'data' => $data,
			'total' => $total,
			'message' => ['Berhasil Mengambil Data']
		]);
    }

    public function kategoriPenjualan(Request $request){
    	$user = $request->user();

		/*
		| seleksi status belum ada , berikan seleksi status ketika perbaikan total
		| diskon belum masuk dalam diskon item. jadi sementara dikurangi dengan diskon transaksi
		*/    	

		/*
		|-----------------------------------------------------------
		|					Keterangan
		|-----------------------------------------------------------
		|	1. subtotal adalah hasil kali dari jumlah dan harga
		|	2. total adalah subtotal  kurang total_diskon dan total_refund
		|
		*/
		$data = $user
		    	->bisnis
		    	->penjualanItem()
		    	->select(DB::raw("
		    			IF(penjualan_item.nama_kategori_menu IS NULL, 'Tidak Dikategorikan', penjualan_item.nama_kategori_menu) as nama_kategori_menu,
			    		SUM(penjualan_item.jumlah) as total_penjualan_item,
			    		SUM(penjualan_item.jumlah_refund) as total_pengembalian_item,
			    		SUM(penjualan_item.total) as total_penjualan_kotor, 
			    		SUM(penjualan_item.total_diskon) + sum((penjualan_item.total * penjualan.jumlah_diskon) / 100 ) as total_diskon, 
			    		SUM(penjualan_item.total_refund) as total_pengembalian_uang,
			    		SUM(penjualan_item.total) 
			    		- SUM(penjualan_item.total_refund) 
			    		- SUM(penjualan_item.total_diskon)
			    		- sum((penjualan_item.total * penjualan.jumlah_diskon) / 100 )
			    		 as total_penjualan_bersih
		    	"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('penjualan_item.outlet_id', $request->outlet_id);

			    	if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('penjualan.tanggal_proses', '>=', $request->tanggal_awal);
			    		$q->where('penjualan.tanggal_proses', '<=', $request->tanggal_akhir);
			    	}
			    	if(	$request->has('pencarian') ){
			    		$q->where('penjualan_item.nama_kategori_menu', 'like', '%'.$request->pencarian.'%');
			    	} 
		    	})
		    	->leftJoin('penjualan','penjualan.id_penjualan','penjualan_item.penjualan_id')
		    	->groupBy('penjualan_item.nama_kategori_menu')
		    	->orderBy('penjualan_item.nama_kategori_menu','asc')
		    	->paginate(15);

		$total = $user
		    	->bisnis
		    	->penjualanItem()
		    	->select(DB::raw("
			    		SUM(penjualan_item.jumlah) as total_penjualan_item,
			    		SUM(penjualan_item.jumlah_refund) as total_pengembalian_item,
			    		SUM(penjualan_item.total) as total_penjualan_kotor, 
			    		SUM(penjualan_item.total_diskon) + sum((penjualan_item.total * penjualan.jumlah_diskon) / 100 ) as total_diskon, 
			    		SUM(penjualan_item.total_refund) as total_pengembalian_uang,
			    		SUM(penjualan_item.total) 
			    		- SUM(penjualan_item.total_refund) - SUM(penjualan_item.total_diskon) 
						- SUM(penjualan_item.total_diskon)
						- sum((penjualan_item.total * penjualan.jumlah_diskon) / 100 )
			    		as total_penjualan_bersih
		    	"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('penjualan_item.outlet_id', $request->outlet_id);

			    	if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('penjualan.tanggal_proses', '>=', $request->tanggal_awal);
			    		$q->where('penjualan.tanggal_proses', '<=', $request->tanggal_akhir);
			    	}
			    	if(	$request->has('pencarian') ){
			    		$q->where('penjualan_item.nama_kategori_menu', 'like', '%'.$request->pencarian.'%');
			    	} 
		    	})
		    	->leftJoin('penjualan','penjualan.id_penjualan','penjualan_item.penjualan_id')
		    	->first();
			$total->total_diskon = round($total->total_diskon);
		    $total->total_pengembalian_uang = round($total->total_pengembalian_uang);
		    $total->total_penjualan_bersih = round($total->total_penjualan_bersih);
		return response()->json([
			'status' => 'success',
			'data' => $data,
			'total' => $total,
			'message' => ['Berhasil Mengambil Data']
		]);
    }

    public function transaksi(Request $request){
    	$user = $request->user();
		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->select('kode_pemesanan','tanggal_proses as tanggal', 'waktu_proses as waktu', 'nama_user', 'total_item', 'subtotal','total')
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
			    			$q->where('kode_pemesanan', 'like', '%'.$request->pencarian.'%');
			    			$q->orWhere('tanggal_proses', 'like', '%'.$request->pencarian.'%');
			    			$q->orWhere('waktu_proses', 'like', '%'.$request->pencarian.'%');
			    			$q->orWhere('nama_user', 'like', '%'.$request->pencarian.'%');
			    		});
		    		}
		    	})
		    	->latest()
		    	->paginate(15);

		
		$total = $user
		    	->bisnis
		    	->penjualan()
		    	->select(DB::raw("SUM(total_item) as total_item, SUM(total) as total"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
			    			$q->where('kode_pemesanan', 'like', '%'.$request->pencarian.'%');
			    			$q->orWhere('tanggal_proses', 'like', '%'.$request->pencarian.'%');
			    			$q->orWhere('waktu_proses', 'like', '%'.$request->pencarian.'%');
			    			$q->orWhere('nama_user', 'like', '%'.$request->pencarian.'%');
			    		});
		    		}
		    	})->first();

		return response([
			'status' => 'success',
			'data' => $data,
			'total' => $total,
			'message' => ['Berhasil Mengambil Data']
		],200);
    }

    /*
    |----------------------------------------------------
    |			DITUNDA LAPORAN BAHAN DAPUR 
    |----------------------------------------------------
    |
    */
    public function bahanDapur(Request $request){
    	$user = $request->user();

		$data = $user
		    	->bisnis
		    	->bahanDapur()
		    	->with('satuan')
		    	->select('nama_bahan_dapur','kode_unik_bahan_dapur')
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);
		    	})
		    	->paginate();
		return response()->json([
			'data' => $data
		]);
    }

    public function diskon(Request $request){
    	$user = $request->user();

		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->select(DB::raw("
		    		nama_diskon,
		    		jumlah_diskon,
	    			COUNT(*) as total_transaksi,
	    			SUM(total_diskon) as total_diskon_kotor,
	    			SUM(IF(status = 'refund', total_diskon, 0)) as total_diskon_dikembalikan,
	    			SUM(total_diskon - IF(status = 'refund', total_diskon, 0)) as total_diskon_bersih
	    		"))
		    	->where(function($q) use ($request){
			    	if($request->has('outlet_id'))
				    	$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
				    			$q->where('nama_diskon', 'like', '%'.$request->pencarian.'%');
				    			$q->orWhere('jumlah_diskon', 'like', '%'.$request->pencarian.'%');
		    			});
		    		}
		    	})
		    	->where('diskon_id', '!=', 0)
		    	->groupBy('nama_diskon')
		    	->groupBy('jumlah_diskon')
		    	->paginate();
		$total = $user
		    	->bisnis
		    	->penjualan()
		    	->select(DB::raw("
	    			COUNT(*) as total_transaksi,
	    			SUM(total_diskon) as total_diskon_kotor,
	    			SUM(IF(status = 'refund', total_diskon, 0)) as total_diskon_dikembalikan,
	    			SUM(total_diskon - IF(status = 'refund', total_diskon, 0)) as total_diskon_bersih
	    		"))
		    	->where(function($q) use ($request){
		    		$q->where('diskon_id', '!=', 0);

			    	if($request->has('outlet_id'))
				    	$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
				    			$q->where('nama_diskon', 'like', '%'.$request->pencarian.'%');
				    			$q->orWhere('jumlah_diskon', 'like', '%'.$request->pencarian.'%');
		    			});
		    		}
		    	})
		    	->first();
		return response([
			'status' => 'success',
			'data' => $data,
			'total' => $total,
			'message' => ['Berhasil Mengambil Data']
		],200);
    }

    public function pajak(Request $request){
    	$user = $request->user();

		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->with('pajak')
		    	->select(DB::raw("pajak_id, sum(subtotal) as jumlah_kena_pajak, sum(total_pajak) as pajak_terkumpul"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
				    	$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
				    		$q->whereHas('pajak',function($q) use ($request){
				    			$q->where('nama_pajak', 'like', '%'.$request->pencarian.'%');
				    			$q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
				    		});
		    			});
		    		}
		    	})
		    	->where('pajak_id', '!=', 0)
		    	->groupBy('pajak_id')
		    	->paginate();

		$total = $user
		    	->bisnis
		    	->penjualan()
		    	->with('pajak')
		    	->select(DB::raw("sum(subtotal) as jumlah_kena_pajak, sum(total_pajak) as pajak_terkumpul"))
		    	->where(function($q) use ($request){
		    		$q->where('pajak_id', '!=', 0);
		    		if($request->has('outlet_id'))
				    	$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
				    		$q->whereHas('pajak',function($q) use ($request){
				    			$q->where('nama_pajak', 'like', '%'.$request->pencarian.'%');
				    			$q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
				    		});
		    			});
		    		}
		    	})
		    	->first();
		return response([
			'status' => 'success',
			'data' => $data,
			'total' => $total,
			'message' => ['Berhasil Mengambil Data']
		], 200);
    }

    public function biayaTambahan(Request $request){
    	$user = $request->user();
    	
		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->with('biayaTambahan')
		    	->select(DB::raw("biaya_tambahan_id, sum(subtotal) as jumlah_kena_biaya_tambahan, sum(total_biaya_tambahan) as biaya_tambahan_terkumpul"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);
		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
				    		$q->whereHas('biayaTambahan',function($q) use ($request){
				    			$q->where('nama_biaya_tambahan', 'like', '%'.$request->pencarian.'%');
				    			$q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
				    		});
		    			});
		    		}
		    	})
		    	->where('biaya_tambahan_id', '!=', 0)
		    	->groupBy('biaya_tambahan_id')
		    	->paginate();
		$total = $user
		    	->bisnis
		    	->penjualan()
		    	->with('biayaTambahan')
		    	->select(DB::raw("sum(subtotal) as jumlah_kena_biaya_tambahan, sum(total_biaya_tambahan) as biaya_tambahan_terkumpul"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);
		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}

		    		if($request->has('pencarian')){
		    			$q->where(function($q) use ($request){
				    		$q->whereHas('biayaTambahan',function($q) use ($request){
				    			$q->where('nama_biaya_tambahan', 'like', '%'.$request->pencarian.'%');
				    			$q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
				    		});
		    			});
		    		}
		    	})
		    	->where('biaya_tambahan_id', '!=', 0)
		    	->first();

		return response([
			'status' => 'success',
			'data' => $data,
			'total' => $total,
			'message' => ['Berhasil Mengambil Data']
		], 200);
    }


}
