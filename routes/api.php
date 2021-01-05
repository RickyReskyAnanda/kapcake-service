<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('kasir/login', 'Kasir\UserController@login');
Route::group([
	// 'middleware' => ['auth:api'], 
	'namespace' => 'TokoOnline', 
	'prefix' => 'toko-online'
	], function(){

	Route::get('profile/{kodeToko}','ProfileController@index');
	Route::get('kategori-produk/{kodeToko}','KategoriProdukController@index');
	Route::get('produk/{kodeToko}','ProdukController@index');
	Route::get('produk/{id}/{kodeToko}','ProdukController@show');

	//pesanan
	Route::get('pesanan/{kodeToko}','PesananController@index');
	Route::post('pesanan/{kodeToko}','PesananController@store');
	Route::get('pesanan/{id}/{kodeToko}','PesananController@show');

	Route::post('pay/{kodeToko}','PaymentController@index'); // ini sinkrosisasi data menu
	// Route::post('email-nota','EmailNotaController@index'); // ini sinkrosisasi data menu
});

Route::post('/notification/handler', 'Api\TagihanController@notificationHandler');


Route::post('version', function(){
	return response(['version' => '1.2.6'], 200);
});
Route::post('version-try', function(){
	return response(['version' => "1.2.6"], 200);
});

Route::post('login', 'Api\UserController@login');
Route::post('signup', 'Api\UserController@signup');

Route::group(['middleware' => 'auth:api'], function(){
	Route::get('dashboard','Api\DashboardController@index');

	Route::get('laporan/ringkasan-penjualan','Api\LaporanController@ringkasanPenjualan');
	Route::get('laporan/penjualan','Api\LaporanController@penjualan');
	Route::get('laporan/kategori-penjualan','Api\LaporanController@kategoriPenjualan');
	Route::get('laporan/transaksi','Api\LaporanController@transaksi');
	Route::get('laporan/bahan-dapur','Api\LaporanController@bahanDapur');
	Route::get('laporan/diskon','Api\LaporanController@diskon');
	Route::get('laporan/pajak','Api\LaporanController@pajak');
	Route::get('laporan/biaya-tambahan','Api\LaporanController@biayaTambahan');

	Route::get('menu','Api\MenuController@index');
	Route::post('menu','Api\MenuController@store');
	Route::get('menu/{menu}','Api\MenuController@show');
	Route::put('menu/{menu}','Api\MenuController@update');
	Route::delete('menu/{menu}','Api\MenuController@destroy');
	
	Route::get('kategori-menu','Api\KategoriMenuController@index');
	Route::get('kategori-menu/{kategoriMenu}','Api\KategoriMenuController@show');
	Route::post('kategori-menu','Api\KategoriMenuController@store');
	Route::put('kategori-menu/{kategoriMenu}','Api\KategoriMenuController@update');
	Route::delete('kategori-menu/{kategoriMenu}','Api\KategoriMenuController@destroy');

	Route::get('kategori-menu-to-menu','Api\KategoriMenuToMenuController@index');
	Route::post('kategori-menu-to-menu','Api\KategoriMenuToMenuController@store');
	
	Route::get('item-tambahan','Api\ItemTambahanController@index');
	Route::get('item-tambahan/{itemTambahan}','Api\ItemTambahanController@show');
	Route::post('item-tambahan','Api\ItemTambahanController@store');
	Route::put('item-tambahan/{itemTambahan}','Api\ItemTambahanController@update');
	Route::delete('item-tambahan/{itemTambahan}','Api\ItemTambahanController@destroy');
	
	Route::get('diskon','Api\DiskonController@index');
	Route::post('diskon','Api\DiskonController@store');
	Route::get('diskon/{diskon}','Api\DiskonController@show');
	Route::put('diskon/{diskon}','Api\DiskonController@update');
	Route::delete('diskon/{diskon}','Api\DiskonController@destroy');

	Route::get('pajak','Api\PajakController@index');
	Route::post('pajak','Api\PajakController@store');
	Route::get('pajak/{pajak}','Api\PajakController@show');
	Route::put('pajak/{pajak}','Api\PajakController@update');
	Route::delete('pajak/{pajak}','Api\PajakController@destroy');

	Route::get('biaya-tambahan','Api\BiayaTambahanController@index');
	Route::post('biaya-tambahan','Api\BiayaTambahanController@store');
	Route::get('biaya-tambahan/{biayaTambahan}','Api\BiayaTambahanController@show');
	Route::put('biaya-tambahan/{biayaTambahan}','Api\BiayaTambahanController@update');
	Route::delete('biaya-tambahan/{biayaTambahan}','Api\BiayaTambahanController@destroy');

	Route::get('tipe-penjualan','Api\TipePenjualanController@index');
	Route::post('tipe-penjualan','Api\TipePenjualanController@store');
	Route::get('tipe-penjualan/{tipePenjualan}','Api\TipePenjualanController@show');
	Route::put('tipe-penjualan/{tipePenjualan}','Api\TipePenjualanController@update');
	Route::delete('tipe-penjualan/{tipePenjualan}','Api\TipePenjualanController@destroy');

	Route::get('bahan-dapur','Api\BahanDapurController@index');
	Route::post('bahan-dapur','Api\BahanDapurController@store');
	Route::get('bahan-dapur/{bahanDapur}','Api\BahanDapurController@show');
	Route::put('bahan-dapur/{bahanDapur}','Api\BahanDapurController@update');
	Route::delete('bahan-dapur/{bahanDapur}','Api\BahanDapurController@destroy');

	Route::get('kategori-bahan-dapur','Api\KategoriBahanDapurController@index');
	Route::post('kategori-bahan-dapur','Api\KategoriBahanDapurController@store');
	Route::get('kategori-bahan-dapur/{kategoriBahanDapur}','Api\KategoriBahanDapurController@show');
	Route::put('kategori-bahan-dapur/{kategoriBahanDapur}','Api\KategoriBahanDapurController@update');
	Route::delete('kategori-bahan-dapur/{kategoriBahanDapur}','Api\KategoriBahanDapurController@destroy');

	Route::get('assign-kategori-bahan-dapur','Api\AssignKategoriBahanDapurController@index');
	Route::put('assign-kategori-bahan-dapur','Api\AssignKategoriBahanDapurController@update');

	Route::get('barang','Api\BarangController@index');
	Route::post('barang','Api\BarangController@store');
	Route::get('barang/{barang}','Api\BarangController@show');
	Route::put('barang/{barang}','Api\BarangController@update');
	Route::delete('barang/{barang}','Api\BarangController@destroy');

	Route::get('kategori-barang','Api\KategoriBarangController@index');
	Route::post('kategori-barang','Api\KategoriBarangController@store');
	Route::get('kategori-barang/{kategoriBarang}','Api\KategoriBarangController@show');
	Route::put('kategori-barang/{kategoriBarang}','Api\KategoriBarangController@update');
	Route::delete('kategori-barang/{kategoriBarang}','Api\KategoriBarangController@destroy');

	Route::get('assign-kategori-barang','Api\AssignKategoriBarangController@index');
	Route::put('assign-kategori-barang','Api\AssignKategoriBarangController@update');

	Route::get('inventori','Api\InventoriController@index');

	Route::get('supplier','Api\SupplierController@index');
	Route::post('supplier','Api\SupplierController@store');
	Route::get('supplier/{supplier}','Api\SupplierController@show');
	Route::put('supplier/{supplier}','Api\SupplierController@update');
	Route::delete('supplier/{supplier}','Api\SupplierController@destroy');

	Route::get('pesanan-pembelian','Api\PesananPembelianController@index');
	Route::post('pesanan-pembelian','Api\PesananPembelianController@store');
	Route::get('pesanan-pembelian/{pesananPembelian}','Api\PesananPembelianController@show');
	Route::put('pesanan-pembelian/{pesananPembelian}','Api\PesananPembelianController@update');
	Route::delete('pesanan-pembelian/{pesananPembelian}','Api\PesananPembelianController@destroy');

	Route::get('penyesuaian-stok','Api\PenyesuaianStokController@index');
	Route::post('penyesuaian-stok','Api\PenyesuaianStokController@store');
	Route::get('penyesuaian-stok/{penyesuaianStok}','Api\PenyesuaianStokController@show');
	Route::put('penyesuaian-stok/{penyesuaianStok}','Api\PenyesuaianStokController@update');
	Route::delete('penyesuaian-stok/{penyesuaianStok}','Api\PenyesuaianStokController@destroy');

	Route::get('transfer','Api\TransferController@index');
	Route::post('transfer','Api\TransferController@store');
	Route::get('transfer/{transfer}','Api\TransferController@show');
	Route::put('transfer/{transfer}','Api\TransferController@update');
	Route::delete('transfer/{transfer}','Api\TransferController@destroy');

	Route::get('pelanggan','Api\PelangganController@index');
	Route::get('pelanggan/{pelanggan}','Api\PelangganController@show');

	Route::get('feedback','Api\FeedBackController@index');
	Route::get('feedback/{feedback}','Api\FeedBackController@show');

	Route::get('staf','Api\StafController@index');
	Route::post('staf','Api\StafController@store');
	Route::get('staf/{staf}','Api\StafController@show');
	Route::put('staf/{staf}','Api\StafController@update');
	Route::delete('staf/{staf}','Api\StafController@destroy');

	Route::get('role','Api\RoleController@index');
	Route::post('role','Api\RoleController@store');
	Route::get('role/{role}','Api\RoleController@show');
	Route::put('role/{role}','Api\RoleController@update');
	Route::delete('role/{role}','Api\RoleController@destroy');

	Route::get('kategori-meja','Api\KategoriMejaController@index');
	Route::post('kategori-meja','Api\KategoriMejaController@store');
	Route::get('kategori-meja/{kategoriMeja}','Api\KategoriMejaController@show');
	Route::put('kategori-meja/{kategoriMeja}','Api\KategoriMejaController@update');
	Route::delete('kategori-meja/{kategoriMeja}','Api\KategoriMejaController@destroy');

	Route::get('meja','Api\MejaController@index');
	Route::post('meja','Api\MejaController@store');
	Route::get('meja/{meja}','Api\MejaController@show');
	Route::put('meja/{meja}','Api\MejaController@update');
	Route::delete('meja/{meja}','Api\MejaController@destroy');

	Route::get('perangkat','Api\PerangkatController@index');
	Route::get('perangkat/{perangkat}','Api\PerangkatController@show');
	Route::put('perangkat/{perangkat}','Api\PerangkatController@update');

	Route::get('outlet','Api\OutletController@index');
	Route::get('outlet/{outlet}','Api\OutletController@show');
	Route::post('outlet','Api\OutletController@store');
	Route::put('outlet/{outlet}','Api\OutletController@update');
	Route::delete('outlet/{outlet}','Api\OutletController@destroy');

	Route::get('akun-bank','Api\AkunBankController@index');
	Route::get('akun-bank/{akunBank}','Api\AkunBankController@show');
	Route::post('akun-bank','Api\AkunBankController@store');
	Route::put('akun-bank/{akunBank}','Api\AkunBankController@update');
	Route::delete('akun-bank/{akunBank}','Api\AkunBankController@delete');

	Route::put('user','Api\UserController@update');
	Route::put('user/change-password','Api\UserController@changePassword');

	//// umum
	Route::get('bank','Api\BankController@index');

	Route::get('bisnis/{bisnis}','Api\BisnisController@show');
	Route::put('bisnis/{bisnis}','Api\BisnisController@update');

	Route::get('item','Api\ItemController@index');

	Route::get('satuan','Api\SatuanController@index');
	Route::post('satuan','Api\SatuanController@store');
	Route::get('satuan/{satuan}','Api\SatuanController@show');
	Route::put('satuan/{satuan}','Api\SatuanController@update');
	Route::delete('satuan/{satuan}','Api\SatuanController@destroy');

	Route::get('aplikasi','Api\AplikasiController@index');
	Route::get('aplikasi/{aplikasi}','Api\AplikasiController@show');

	Route::get('profil', 'Api\UserController@profil');
	Route::put('profil/outlet-terpilih', 'Api\UserController@updateOutletTerpilih');

	Route::put('profil/jenis-item-terpilih', 'Api\UserController@updateJenisItemTerpilih');

	// TAGIHAN
	Route::get('/tagihan', 'Api\TagihanController@index');
	Route::post('/tagihan', 'Api\TagihanController@store');
	Route::put('/tagihan/{tagihan}', 'Api\TagihanController@submitSnap');
	Route::put('/tagihan/{tagihan}/status', 'Api\TagihanController@statusTagihan');
	Route::get('/tagihan/get/updated', 'Api\TagihanController@updatedTagihan');
});
Route::group([
    'namespace' => 'Auth',
    'prefix' => 'password'
	], function () {    
    Route::post('create', 'ResetPasswordController@create');
    Route::get('find/{token}', 'ResetPasswordController@find');
    Route::post('reset', 'ResetPasswordController@reset');
});




Route::post('kasir/login', 'Kasir\UserController@login');
Route::group([
	'middleware' => ['auth:api'], 
	'namespace' => 'Kasir', 
	'prefix' => 'kasir'
	], function(){
	Route::get('outlet','OutletController@index');

	Route::get('pelanggan','PelangganController@index');
	Route::post('pelanggan','PelangganController@store');

	Route::get('pemesanan','PemesananController@index');
	Route::post('pemesanan','PemesananController@store');
	Route::get('pemesanan/show','PemesananController@show');

	// -----
	Route::get('penjualan','PenjualanController@index'); // ini sinkrosisasi data penjualan
	Route::post('penjualan','PenjualanController@store');
	Route::get('penjualan/show','PenjualanController@show');

	Route::get('printer','PrinterController@index'); // ini sinkrosisasi data penjualan
	Route::post('printer','PrinterController@store');

	Route::get('menu','MenuController@index'); // ini sinkrosisasi data menu

	Route::get('meja','MejaController@index'); // ini sinkrosisasi data meja

	Route::get('diskon','DiskonController@index'); // ini sinkrosisasi data diskon
	Route::get('biaya-tambahan','BiayaTambahanController@index'); // ini sinkrosisasi data diskon
	Route::get('jenis-pemesanan','TipePenjualanController@index'); // ini sinkrosisasi data diskon



	// Route::post('pelanggan/{outlet}','PelangganController@store');
	Route::post('update-profil','UserController@updateProfil'); // ini sinkrosisasi data menu
	Route::post('update-password','UserController@updatePassword'); // ini sinkrosisasi data menu

	Route::post('perangkat/logout','PerangkatController@logout'); // ini sinkrosisasi data menu

	Route::post('email-nota','EmailNotaController@index'); // ini sinkrosisasi data menu
});


// Route::post('kasir-potrait/login', 'KasirPotrait\UserController@login');
// Route::group([
// 	'middleware' => ['auth:api'], 
// 	'namespace' => 'KasirPotrait', 
// 	'prefix' => 'kasir-potrait'
// ], function(){
// 	Route::get('outlet','OutletController@index');

// 	Route::get('pelanggan','PelangganController@index');
// 	Route::post('pelanggan','PelangganController@store');

// 	Route::get('pemesanan','PemesananController@index');
// 	Route::post('pemesanan','PemesananController@store');

// 	// -----
// 	Route::get('penjualan','PenjualanController@index'); // ini sinkrosisasi data penjualan
// 	Route::post('penjualan','PenjualanController@store');
// 	Route::get('penjualan/{kodePemesanan}','PenjualanController@show');

// 	Route::get('printer','PrinterController@index'); // ini sinkrosisasi data penjualan
// 	Route::post('printer','PrinterController@store');

// 	Route::get('menu','MenuController@index'); // ini sinkrosisasi data menu
// 	Route::get('kategori-menu','KategoriMenuController@index'); // ini sinkrosisasi data kategori menu

// 	Route::get('meja','MejaController@index'); // ini sinkrosisasi data meja

// 	Route::get('pajak','PajakController@index'); // ini sinkrosisasi data diskon
// 	Route::get('diskon','DiskonController@index'); // ini sinkrosisasi data diskon
// 	Route::get('biaya-tambahan','BiayaTambahanController@index'); // ini sinkrosisasi data diskon
// 	Route::get('jenis-pemesanan','JenisPemesananController@index'); // ini sinkrosisasi data diskon
// 	Route::get('satuan','SatuanController@index'); // ini sinkrosisasi data diskon

// 	// Route::post('pelanggan/{outlet}','PelangganController@store');
// 	Route::post('update-profil','UserController@updateProfil'); // ini sinkrosisasi data menu
// 	Route::post('update-password','UserController@updatePassword'); // ini sinkrosisasi data menu

// 	Route::post('perangkat/logout','PerangkatController@logout'); // ini sinkrosisasi data menu

// 	Route::post('email-nota','EmailNotaController@index'); // ini sinkrosisasi data menu

// });


Route::post('dapur/login', 'Dapur\UserController@login');
Route::group([
	'middleware' => ['auth:api'], 
	'namespace' => 'Dapur', 
	'prefix' => 'dapur'
], function(){
	Route::get('pesanan','PesananController@index');
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




