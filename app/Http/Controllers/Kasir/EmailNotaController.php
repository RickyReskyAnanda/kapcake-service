<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Nota;
use Mail;
class EmailNotaController extends Controller
{
    public function index(Request $request){
    	$request->validate([
    		'email' => 'required|email',
    		'pesanan' => 'required',
    	]);
      // $dataPesanan['url_logo']= "";
    	Mail::to($request->email)
        ->send(new Nota($request->pesanan));
 
      	if (Mail::failures()) {
           return response('error',500);
      	}else{
           return response('success',200);
        }
    }
}
