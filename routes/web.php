<?php
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:cache');
    return 'DONE'; //Return anything
});
Route::get('/','LandingPageController@index');
Route::get('version',function(){
	return "version";
});

// \





//// PAYMENT ONLY
Route::post('/payment/finish', function(){
    return redirect('https://bo.kapcake.com/app/pengaturan/tagihan');
})->name('tagihan.finish');


Route::post('/payment/unfinish', function(){
    return redirect('https://bo.kapcake.com/app/pengaturan/tagihan');
})->name('tagihan.unfinish');


Route::post('/payment/error', function(){
    return redirect('https://bo.kapcake.com/app/pengaturan/tagihan');
})->name('tagihan.error');
Route::post('/notification/handler', 'Api\TagihanController@notificationHandler')->name('notification.handler');
 ////.//
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'HomeController@import');

Auth::routes();
Route::get('verifikasi-email/{token}','Auth\RegisterController@confirmAccount');

Route::get('notifikasi', 'NotifikasiController@index')->name('notifikasi.index');

Auth::routes();


// Route::get('/{nama-toko}','LandingPageController@index');

// Route::get('/home', 'HomeController@index')->name('home');
// MAIL_DRIVER=smtp
// MAIL_HOST=mail.kapcake.com
// MAIL_PORT=465
// MAIL_USERNAME=no-reply@kapcake.com
// MAIL_PASSWORD=NFNLS0mqkTN
// MAIL_ENCRYPTION=ssl



// Route::get('/payment', 'PaymentController@index');
// Route::post('/payment/finish', function(){
//     return redirect()->route('welcome');
// })->name('payment.finish');

// Route::get('/payment/unfinish', function(){
//     return redirect()->route('welcome');
// })->name('payment.unfinish');
 
// Route::post('/payment/store', 'PaymentController@submitpayment')->name('payment.store');
// Route::post('/payment/notification', 'PaymentController@notificationHandler')->name('payment.notification');
 

// Route::post('/finish', function(){
//     return redirect()->route('welcome');
// })->name('payment.finish');
 
// Route::post('/payment/store', 'PaymentController@submitPayment')->name('payment.store');
// Route::post('/notification/handler', 'PaymentController@notificationHandler')->name('notification.handler');
 
