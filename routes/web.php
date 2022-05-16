<?php

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

Route::get('/', function () {
    // return view('welcome');
    return redirect('login');
});
Route::get('/print/{id}', 'HomeController@print');

Auth::routes();
Route::get('/transaksi/print_barcode/{id}', 'TransaksiController@printBarcode')->name('transaksi.print_barcode');


Route::middleware(['auth'])->group(function () {

    Route::get('/get_barang', 'BarangController@getData')->name('getBarang');
    Route::get('/get_stok', 'BarangController@getStokBarang')->name('getStokBarang');
    Route::get('/get_pembelian', 'PembelianController@getData')->name('getPembelianBarang');
    Route::get('/get_penjualan', 'TransaksiController@getDataPenjualan')->name('getPenjualan');
    Route::get('/get_barang_transaksi', 'TransaksiController@getData')->name('getBarangForTransaksi');
    Route::get('/get_barang_harga', 'BarangController@getDataHarga')->name('getHargaBarang');
    Route::get('/get_search_toko', 'PartnerController@getSearchPartner')->name('getSearchPartner');
    Route::get('/get_partner_confirmation', 'PartnerController@getPartnerConfirmation')->name('getPartnerConfirmation');
    Route::get('/get_partner_saya', 'PartnerController@getMyPartner')->name('getMyPartner');
    Route::get('/get_partner_barang', 'PartnerController@getBarangPartner')->name('getBarangPartner');
    Route::get('/get_partner_barang_confirmation', 'PartnerController@getBarangPartnerConfirmation')->name('getBarangPartnerConfirmation');
    Route::post('/print_partner_barang_confirmation/{id}', 'PartnerController@printPartnerTransaction')->name('printPartnerTransaction');
    Route::resource('/warung', 'WarungController');

    Route::middleware(['has.warung'])->group(function () {
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/transaksi', 'TransaksiController@index')->name('transaksi');
        Route::get('/transaksi/print/{id}', 'TransaksiController@print')->name('transaksi.print');
        Route::get('/transaksi/print_preview/{id}', 'TransaksiController@printJs')->name('transaksi.print_preview');
        Route::get('/transaksi/{id}', 'TransaksiController@show')->name('transaksi.show');
        Route::post('/transaksi', 'TransaksiController@store')->name('transaksi.store');
        Route::get('barang/duplicate/{id}', 'BarangController@duplicate')->name('barang.duplicate');
        Route::post('barang/active/{id}', 'BarangController@activeBarang')->name('barang.active');
        Route::resource('/barang', 'BarangController');
        Route::resource('/pembelian', 'PembelianController');
        Route::get('/stok', 'BarangController@stok')->name('stok');
        Route::get('/harga-dasar', 'BarangController@hargaBarang')->name('harga_dasar');
        Route::get('/laporan', 'TransaksiController@laporan')->name('laporan');
        Route::get('/laporan/transaksi/{id}', 'TransaksiController@laporanShow')->name('laporan.show');
        Route::get('/partner/search', 'PartnerController@search')->name('partner.search');
        Route::post('/partner/send-invitation', 'PartnerController@sendInvitation')->name('partner.send-invitation');
        Route::post('/partner/remove-invitation', 'PartnerController@removeInvitation')->name('partner.remove-invitation');
        Route::post('/partner/confirm-invitation', 'PartnerController@confirmInvitation')->name('partner.confirm-invitation');
        Route::get('/partner/list-confirmation', 'PartnerController@listConfirmation')->name('partner.list-confirmation');
        Route::get('/partner/me', 'PartnerController@myPartner')->name('partner.me');
        Route::get('/partner/profile/{id}', 'PartnerController@profile')->name('partner.profile');
        Route::get('/partner/product-colaboration/{id}', 'PartnerController@getProductColaboration')->name('partner.get-product-colaboration');
        Route::get('/partner/transaction/{id}', 'PartnerController@getTransaction')->name('partner.transaction');
        Route::post('/partner/product-colaboration/{id}', 'PartnerController@productColaboration')->name('partner.product-colaboration');
        Route::post('/partner/product-joined/{id}', 'PartnerController@productJoined')->name('partner.product-joined');
        Route::post('/partner/payment', 'PartnerController@payment')->name('partner.payment');
        Route::get('/produksi/bulat', 'ProduksiController@bulatPress')->name('produksi.bulat');
        Route::post('/produksi/print', 'ProduksiController@printBulat')->name('produksi.print');
        Route::get('/auth/change-password', 'AuthController@changePasswordView')->name('auth.change-password-view');
        Route::post('/auth/change-password', 'AuthController@changePassword')->name('auth.change-password');
    });
});
