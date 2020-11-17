<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;

class PaymentController extends Controller
{
    public function index($kodeToko, Request $request){
    	$params = $request->all();

    	if($params['payment_method'] == 'gopay')
	    	return $this->midtrans($kodeToko, $params);
    }

    private function midtrans($kodeToko, $params){
    	$items = [];

    	foreach ($params['items'] as $k => $v) {
    		array_push($items, [
		      	"id" => $v['variant_menu_id'],
		      	"price" => $v['price'],
		      	"quantity" => $v['qty'],
		      	"name" => $v['menu_name'].($v['variant_menu_name'] != '' ? ' - '.$v['variant_menu_name'] : '')
	    	]);
    	}


    	// foreach ($params['discounts'] as $k => $v) {
    	// 	array_push($items, [
		   //    	"id" => $v['discount_id'],
		   //    	"price" => $v['total'] * -1,
		   //    	"quantity" => 1,
		   //    	"name" => $v['discount_name']
	    // 	]);
    	// }

    	$order = [
		  	"payment_type" => "gopay",
		  	"transaction_details" => [
		    	"order_id" => 'order_'.date('YmdHis').'_'.$kodeToko.'_'.$params['total'],
		    	"gross_amount" => $params['total']
		  	],
		  	"item_details" => $items,
		  	"customer_details" => [
		    	"name" => $params['delivery_address']['contact_name'],
		    	"phone" => $params['delivery_address']['phone_number']
		  	],
		  	"gopay" => [
		    	"enable_callback" => true,
		    	"callback_url" => "someapps://callback"
		  	],
		  	"custom_expiry" => [
			    "expiry_duration" => 5,
			    "unit" => "minute"
			]
		];
		// return json_encode($order);
    	$client = new Client(['base_uri' => 'https://api.sandbox.midtrans.com']);

		$req =  $client->request('POST', '/v2/charge', [
		    'headers' => [
		        'Accept' => 'application/json',
			  'Content-Type' => 'application/json',
			  'Authorization' => 'Basic U0ItTWlkLXNlcnZlci1yMl84R0NYakVwUnp2VHpOTmlDQnh2V2Y6',
		    ],
		    'body' => json_encode($order)
		]);

		$res = json_decode($req->getBody());
		dd($res);
		return $res->status_code;
    }

}
