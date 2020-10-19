<?php

namespace App\Http\Controllers\TokoOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;

class PembayaranController extends Controller
{
    public function index(Request $request){
    	$client = new Client(['base_uri' => 'https://api.sandbox.midtrans.com']);

		$req =  $client->request('POST', '/v2/charge', [
		    'headers' => [
		        'Accept' => 'application/json',
				  'Content-Type' => 'application/json',
				  'Authorization' => 'Basic U0ItTWlkLXNlcnZlci1yMl84R0NYakVwUnp2VHpOTmlDQnh2V2Y6',
				  'Cookie' => '__cfduid=d10c77fb18298b14a071b24a2371cfa651602250123'
		    ],
		    'body' => '{
						  "payment_type": "gopay",
						  "transaction_details": {
						    "order_id": "order'.date('YmdHis').'",
						    "gross_amount": 275000
						  },
						  "item_details": [
						    {
						      "id": "id1",
						      "price": 275000,
						      "quantity": 1,
						      "name": "Bluedio H+ Turbine Headphone with Bluetooth 4.1 -"
						    }
						  ],
						  "customer_details": {
						    "first_name": "Budi",
						    "last_name": "Utomo",
						    "email": "budi.utomo@midtrans.com",
						    "phone": "081223323423"
						  },
						  "gopay": {
						    "enable_callback": true,
						    "callback_url": "someapps://callback"
						  }
						}'
		]);   
		$res = json_decode($req->getBody());
		return $res->status_code;
    }


}
