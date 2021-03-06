<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagihanIndex as TagihanIndexResource;
use App\Http\Resources\TagihanShow as TagihanShowResource;
use DB;
use Veritrans_Config;
use Veritrans_Snap;
use Veritrans_Notification;
use App\PaketLamaBerlangganan;
use App\Paket;
use App\Tagihan;
use App\OutletUser;
use App\Outlet;


class PesananController extends Controller
{
    

    /**
     * Make request global.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;
 
    /**
     * Class constructor.
     *
     * @param \Illuminate\Http\Request $request User Request
     *
     * @return void
     */
    // public function __construct(Request $request)
    // {
    //     $this->request = $request;
 
    //     // Set midtrans configuration
    //     Veritrans_Config::$serverKey = config('services.midtrans.serverKey');
    //     Veritrans_Config::$isProduction = config('services.midtrans.isProduction');
    //     Veritrans_Config::$isSanitized = config('services.midtrans.isSanitized');
    //     Veritrans_Config::$is3ds = config('services.midtrans.is3ds');
    // }

 
    /**
     * Submit donation.
     *
     * @return array
     */



    public function index(Request $request)
    {
        try {   
           $data =  $request->user()->bisnis
                        ->tagihan()
                        ->latest()
                        ->paginate();
            return TagihanIndexResource::collection($data);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => ["Terjadi Kesalahan"]
            ], 500);
        }
    }

    public function store($kodeToko, Request $request)
    {

        $req = $request->validate([
            'cart.date' => 'required',
            'cart.time' => 'required',
            'cart.items' => 'required',
            'cart.count_items' => 'required|integer',
            'cart.subtotal' => 'required|integer',
            'cart.total' => 'required|integer',
            'kode_toko' => 'required|string',
        ]);

        $outlet = Outlet::select('id_outlet')
                    ->where('kode_toko_online',$req['kode_toko'])
                    ->first();
        
        $data  = $outlet->tokoPesanan()->create([
            'kode_toko' => $req['kode_toko'],
            'jumlah_total' => $req['cart']['count_items'],
            'subtotal' => $req['cart']['subtotal'],
            'tanggal_pesan' => date('Y-m-d',strtotime($req['cart']['date'])),
            'waktu_pesan' => date('H:i:s',strtotime($req['cart']['time'])),
            'total' => $req['cart']['total'],
        ]);

        $total = 0;
        foreach ($req['cart']['items'] as $k => $v) {
            $data->items()->updateOrCreate(
                ['variasi_menu_id' => $v['variant_menu_id']],
                [
                    'menu_id' => $v['menu_id'], 
                    'nama_menu' => $v['menu_name'],
                    'kategori_menu_id' => null,
                    'nama_kategori_menu' => null,
                    'variasi_menu_id' => $v['variant_menu_id'],
                    'nama_variasi_menu' => $v['variant_menu_name'],
                    'jumlah' => $v['qty'],
                    'harga' => $v['price'],
                    'total' => (int)$v['qty']*(int)$v['price'],
                    'catatan' => $v['note'],
                ]
            );

            $total += (int)$v['qty'] * (int)$v['price'];
        }
        $data->subtotal = $total;
        $data->total =$total;
        $data->save();
        $data = $data->load('items');


         return response()->json([
            'status' => 'success',
            'data' => new \App\Http\Resources\TokoOnline\Pesanan($data),
            'message' => ["Berhasil mengirim data"]
        ], 200);


        /*********** POTENSI ERROR **********
            1. apa bila diganti kode tokonya dengan pesanan sekarang
        */        
 
        // return response(
        //     [
        //         'data' => new TagihanShowResource($tagihan),
        //         'jatuh_tempo' => $tagihan->jatuh_tempo,
        //         'status' => 'success'
        //     ], 200
        // );
    }

    // public function submitSnap(Request $request)
    // {
    //     $req = $request->validate(['id'=>'required|integer']);
    //     /// menghitung subtotal
    //     $tagihan = Tagihan::find($req['id']);
    //     $tagihan->nomor = (int)$tagihan->nomor + 1; 

    //     $entry = [];
    //     foreach ($tagihan->entry as $key => $v) {
    //         array_push($entry, [
    //             'id'       => $v->id_tagihan_entry,
    //             'price'    => $v->total,
    //             'quantity' => 1,
    //             'name'     => $v['deskripsi']
    //         ]);
    //     }
    //     $tagihan->kode_invoice = $tagihan->kode_invoice.((int)$tagihan->nomor > 1 ?  '('.$tagihan->nomor.')' : '');
    //     // Buat transaksi ke midtrans kemudian save snap tokennya.
    //     $payload = [
    //         'transaction_details' => [
    //             'order_id'      => $tagihan->kode_invoice,
    //             'gross_amount'  => $tagihan->total,
    //         ],
    //         'customer_details' => [
    //             'first_name'    => $tagihan->name,
    //             'email'         => $tagihan->email,
    //             // 'phone'         => '08888888888',
    //             // 'address'       => '',
    //         ],
    //         'item_details' => $entry,
    //         "expiry" => [
    //             "start_time" =>   date('Y-m-d H:i:s', strtotime($tagihan->jatuh_tempo . ' - 1 day'))." +0800",
    //             "unit" =>  "days",
    //             "duration" => 1
    //         ],
    //     ];

    //     $snapToken = Veritrans_Snap::getSnapToken($payload);
    //     $tagihan->snap_token = $snapToken;
    //     $tagihan->save();
        
    //     // Beri response snap token
    //     $this->response['snap_token'] = $snapToken;

    //     return response()->json($this->response);
    // }
 
    /**
     * Midtrans notification handler.
     *
     * @param Request $request
     * 
     * @return void
     */
    // public function notificationHandler(Request $request)
    // {
    //     $notif = new Veritrans_Notification();
    //     \DB::transaction(function() use($notif) {
 
    //       $transaction = $notif->transaction_status;
    //       $type = $notif->payment_type;
    //       $orderId = $notif->order_id;
    //       $fraud = $notif->fraud_status;
    //       $tagihan = Tagihan::where('kode_invoice',$orderId )->first();
    //         // $tagihan->status = 'success';
    //         // $tagihan->save();
 
    //       if ($transaction == 'capture') {
 
    //         // For credit card transaction, we need to check whether transaction is challenge by FDS or not
    //         if ($type == 'credit_card') {
 
    //           if($fraud == 'challenge') {
    //             // TODO set payment status in merchant's database to 'Challenge by FDS'
    //             // TODO merchant should decide whether this transaction is authorized or not in MAP
    //             // $tagihan->addUpdate("Transaction order_id: " . $orderId ." is challenged by FDS");
    //             $tagihan->setPending();
    //           } else {
    //             // TODO set payment status in merchant's database to 'Success'
    //             // $tagihan->addUpdate("Transaction order_id: " . $orderId ." successfully captured using " . $type);
    //             $tagihan->setSuccess();
    //           }
 
    //         }
 
    //       } elseif ($transaction == 'settlement') {
 
    //         // TODO set payment status in merchant's database to 'Settlement'
    //         // $tagihan->addUpdate("Transaction order_id: " . $orderId ." successfully transfered using " . $type);
    //         $tagihan->setSuccess();
            
    //         foreach($tagihan->entry as $k => $d){

    //             $jmlAktivasi = $tagihan->bisnis
    //                     ->aktivasi()
    //                     ->where('paket_id',$d['paket_id'])
    //                     ->where('outlet_id',$d['outlet_id'])
    //                     ->where('is_batal', 0)
    //                     ->count();

    //             //ambil lama berlangganan dan dapatkan data tanggan kadaluarsannya
    //             $paket = Paket::find($d->paket_id);
    //             $paketLamaBerlangganan = PaketLamaBerlangganan::find($d->paket_lama_berlangganan_id);

    //             if($jmlAktivasi < 1)
    //             {
    //                 $tagihan->bisnis
    //                     ->aktivasi()
    //                     ->where('outlet_id', $d['outlet_id'])
    //                     ->update([
    //                         'is_batal' => 1,
    //                     ]);
    //                 // menambahkan data aktivasi yang baru untuk paket yang berbeda saja 
    //                 $tglKadaluarsa = date( 'Y-m-d', strtotime(date('Y-m-d').' + '.$paketLamaBerlangganan->jumlah_dalam_bulan.' months'));
    //                 $tglTambahanWaktu = date('Y-m-d',strtotime($tglKadaluarsa.' + 2 weeks'));
    //                 $tagihan->bisnis
    //                     ->aktivasi()
    //                     ->create([
    //                         'outlet_id' => $d->outlet_id,
    //                         'paket_id' => $d->paket_id,
    //                         'nama_paket' => $paket->nama_paket,
    //                         'kadaluarsa' => $tglKadaluarsa,
    //                         'tambahan_waktu' => $tglTambahanWaktu,
    //                         'lama_berlangganan' => $paketLamaBerlangganan->keterangan,
    //                         'is_kitchen' => 1,
    //                         'is_digimenu' => 1,
    //                         'is_batal' => 0,
    //                     ]);
                    
    //             }elseif($jmlAktivasi >= 1){
    //                 $aktivasi = $tagihan->bisnis
    //                     ->aktivasi()
    //                     ->where('outlet_id',$d->outlet_id)
    //                     ->where('paket_id', $d->paket_id)
    //                     ->first();
    //                 $tglKadaluarsa = date( 'Y-m-d', strtotime($aktivasi->kadaluarsa.' + '.$paketLamaBerlangganan->jumlah_dalam_bulan.' months'));
    //                 $tglTambahanWaktu = date('Y-m-d',strtotime($tglKadaluarsa.' + 2 weeks'));
    //                 $tagihan->bisnis
    //                     ->aktivasi()
    //                     ->where('outlet_id',$d->outlet_id)
    //                     ->where('paket_id', $d->paket_id)
    //                     ->update([
    //                         'nama_paket' => $paket->nama_paket,
    //                         'kadaluarsa' => $tglKadaluarsa,
    //                         'tambahan_waktu' => $tglTambahanWaktu,
    //                         'lama_berlangganan' => $paketLamaBerlangganan->keterangan,
    //                         'is_kitchen' => 1,
    //                         'is_digimenu' => 1,
    //                         'is_batal' => 0,
    //                     ]);
    //             }


    //             // proses penandaan akses user admin
    //             $userAdmin = $tagihan->bisnis
    //                     ->user()
    //                     ->where('is_super_admin',1)
    //                     ->first();
    //             foreach ($tagihan->entry as $key => $value) {
    //                 OutletUser::firstOrCreate([
    //                         'bisnis_id' => $value->bisnis_id,
    //                         'outlet_id' => $value->outlet_id,
    //                         'user_id' => $userAdmin->id ?? 0
    //                     ], 
    //                     [
    //                         'bisnis_id' => $value->bisnis_id,
    //                         'outlet_id' => $value->outlet_id,
    //                         'user_id' => $userAdmin->id ?? 0
    //                     ]
    //                 );
    //             }
    //         }
    //       } elseif($transaction == 'pending'){
    //         // TODO set payment status in merchant's database to 'Pending'
    //         // $tagihan->addUpdate("Waiting customer to finish transaction order_id: " . $orderId . " using " . $type);
    //         $tagihan->setPending();
 
    //       } elseif ($transaction == 'deny') {
 
    //         // TODO set payment status in merchant's database to 'Failed'
    //         // $tagihan->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is Failed.");
    //         $tagihan->setFailed();
 
    //       } elseif ($transaction == 'expire') {
 
    //         // TODO set payment status in merchant's database to 'expire'
    //         // $tagihan->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is expired.");
    //         $tagihan->setExpired();
 
    //       } elseif ($transaction == 'cancel') {
 
    //         // TODO set payment status in merchant's database to 'Failed'
    //         // $tagihan->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is canceled.");
    //         $tagihan->setFailed();
 
    //       }
 
    //     });
 
    //     return;
    // }

    // public function updatedTagihan(Request $request){
    //       // $tagihan = Tagihan::where('kode_invoice',$orderId)->first();
    //     $tagihan = $request->user()->bisnis
    //                     ->tagihan()
    //                     ->where('status','pending')
    //                     ->where('jatuh_tempo', '<=',date('Y-m-d H:i:s'))
    //                     // ->latest()
    //                     ->update([
    //                         'status' => 'cancel',
    //                     ]);  
    //      $tagihan = $request->user()->bisnis
    //                     ->tagihan()
    //                     ->where('status','pending')
    //                     ->where('jatuh_tempo', '>',date('Y-m-d H:i:s + 2 minutes'))
    //                     ->latest()
    //                     ->first();  
    //     if($tagihan)
    //         return response(
    //             [
    //                 'data' => new TagihanShowResource($tagihan),
    //                 'jatuh_tempo' => $tagihan->jatuh_tempo,
    //                 'status' => 'success'
    //             ], 200
    //         );
    //     else
    //         return response(
    //             [
    //                 'data' => [],
    //                 'message' => 'Data tagihan tidak tersedia',
    //                 'status' => 'success'
    //             ], 200
    //         );
    // }

    //put
    // public function statusTagihan(Request $request){
    //     $request->user()->bisnis
    //                     ->tagihan()
    //                     ->where('status','pending')
    //                     ->update([
    //                       'status' => 'cancel'
    //                     ]);  
    //     return response(
    //         [
    //             'status' => 'success',
    //             'message' => 'Berhasil Memperbaharui Data',
    //         ], 200
    //     );
    // }
}