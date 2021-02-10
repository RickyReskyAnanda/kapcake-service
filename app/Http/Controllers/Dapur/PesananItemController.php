<?php

namespace App\Http\Controllers\Dapur;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PesananItemController extends Controller
{
    // public function index(Request $request){
    //     try {   
	   //  	return response([
	   //  		'status' => 'success',
	   //  		'data' =>  $this->data(),
	   //  		'message' => 'Berhasil mengambil data'
	   //  	],200);
    // 	} catch (\Exception $e) {
    //         return response([
    //             'status' => 'error',
    //             'message' =>  "Terjadi Kesalahan"
    //         ], 500);
    //     }
    // }

    public function update(Request $request){
    	$data = $request->validate([
            'bisnis_id' => 'required|integer',
            'outlet_id' => 'required|integer',
            'pemesanan_id' => 'required|integer',
            'pemesanan_item_id' => 'required|integer',
        ]);

    	try {   
	    	return response([
	    		'status' => 'success',
	    		'message' => 'Berhasil memperbarui data'
	    	],200);
    	} catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' =>  "Terjadi Kesalahan"
            ], 500);
        }
    }

  //   private function data(){
  //   	return [
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ],
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ], 
		//   [
		//     "nama_pelayan"=> "Sam Thrope", 
		//     "waktu_pemesanan"=> "10=>12", 
		//     "id_pesanan"=> 1, 
		//     "kode_pemesanan"=> 32003830430, 
		//     "pesanan"=> [
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "", 
		//             "nama_menu"=> "Jus Alpukat", 
		//             "jumlah"=> 3
		//           ], 
		//           [
		//             "nama_variasi_menu"=> "Spesial", 
		//             "nama_menu"=> "Jus Mangga", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Dine In"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "adasd", 
		//             "nama_menu"=> "Pisang Coklat", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Booking"
		//       ], 
		//       [
		//         "menu"=> [
		//           [
		//             "nama_variasi_menu"=> "Panas", 
		//             "nama_menu"=> "Lemon Tea", 
		//             "jumlah"=> 1
		//           ]
		//         ], 
		//         "nama_tipe_penjualan"=> "Take Away"
		//       ]
		//     ], 
		//     "nama_meja"=> "A2"
		//   ]
		// ];
  //   }
}
