<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/associated', function () {
    return view('auth.associated');
})->name('associated');

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->get('/', 'DashboardController@index')->name('dashboard');
Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->post('/notification/api/get', 'DashboardController@api_get_notif')->name('get_notification');

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('order')->group(function () {
    Route::middleware(['auth.order'])->group(function(){
        Route::get('/detail/{id}', 'OrderController@show_detail')->name('order-detail');
        Route::post('/api/update_status','OrderController@update_status_order')->name('update_status_order');
        Route::post('/api/request_awb','OrderController@request_awb')->name('request_awb');
        Route::post('/api/upload_tax','OrderController@api_upload_tax')->name('upload_tax');
    });

    Route::get('/', 'OrderController@index')->name('order');
	Route::post('/api/get_list', 'OrderController@api_get_list')->name('get_list_order');
    Route::get('/download/bast/{id}','OrderController@download_bast')->name('download_bast');
    Route::get('/download/po/{id}','OrderController@download_po')->name('download_po');
    Route::get('/download/invoice/{id}','OrderController@download_invoice')->name('download_invoice');
    Route::get('/download/label_jne/{id}','OrderController@download_label_jne')->name('download_label_jne');
});

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('nego')->group(function () {
    Route::middleware(['auth.nego'])->group(function(){
        Route::post('/api/get_detail', 'NegoController@apiGetDetail')->name('get_detail_nego');
        Route::post('/api/action_nego', 'NegoController@apiActionNego')->name('action_nego');
    });

    Route::get('/', 'NegoController@index')->name('nego');
    Route::post('/api/get_list', 'NegoController@apiGetList')->name('get_list_nego');
    Route::get('/download/{id}','NegoController@download')->name('download_nego');
});

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('product')->group(function () {
    Route::get('/', 'ProductController@index')->name('product');
    Route::get('/detail/{id}', 'ProductController@show_detail')->name('product-detail');
    Route::get('/add', 'ProductController@show_form_add')->name('product-form-add');
    Route::get('/upload', 'ProductController@show_form_upload')->name('product-form-upload');
    Route::post('/api/get_list', 'ProductController@api_get_list')->name('get_list_product');
    Route::post('/api/get_category', 'ProductController@api_get_category')->name('get_list_category');
    Route::post('/api/get_price', 'ProductController@api_get_price')->name('get_list_price');
    Route::post('/api/get_bulk_history', 'ProductController@api_get_bulk_history')->name('get_list_bulk_history');
    Route::post('/api/get_bulk_history_detail', 'ProductController@api_get_bulk_history_detail')->name('get_list_bulk_history_detail');
    Route::post('/api/get_list_book', 'ProductController@api_get_list_book')->name('get_list_book');
    Route::post('/api/store_item', 'ProductController@api_store_item')->name('store_product');
    Route::post('/api/update_item/{id}', 'ProductController@api_update_item')->name('update_product');
    Route::post('/api/update_status_item/{id}', 'ProductController@api_update_status_item')->name('update_status_product');
    Route::post('/api/check_item_order/{id}', 'ProductController@api_check_item_in_ongoing_order')->name('check_item_order');
    Route::post('/api/adjust_stock/{id}', 'ProductController@api_adjust_stock')->name('adjust_stock');
    Route::post('/api/upload_images', 'ProductController@api_upload_images')->name('upload_product_images');
    Route::post('/api/upload_bulk', 'ProductController@api_store_bulk')->name('store_product_bulk');
});

Route::middleware(['auth:sanctum', 'verified', 'associated'])->prefix('vendor')->group(function () {
    Route::get('/', 'VendorController@index')->name('vendor');
    Route::get('/edit', 'VendorController@edit')->name('vendor-edit');
    Route::post('/submit', 'VendorController@submit')->name('vendor-submit');
});

Route::middleware(['auth:sanctum', 'verified', 'associated'])->prefix('region')->group(function () {
    Route::get('/', 'RegionController@index')->name('region');
    Route::get('/district/{region_id}', 'RegionController@district')->name('region-district');
});


Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('complaint')->group(function () {
    Route::get('/', 'ComplaintController@index')->name('complaint');
    Route::post('/api/get_list', 'ComplaintController@api_get_list')->name('get_list_complaint');
    Route::post('/api/get_list_message', 'ComplaintController@api_get_list_message')->name('get_list_complaint_message');
    Route::post('/api/sent_message', 'ComplaintController@api_store_message')->name('store_message_complaint');
    Route::post('/api/confirm_action', 'ComplaintController@api_set_action')->name('set_action_complaint');
    Route::post('/api/set_as_read', 'ComplaintController@api_set_read')->name('set_read_complaint');
    Route::get('/download/{id}','ComplaintController@download')->name('download_complaint');
});

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('messaging')->group(function () {
    Route::get('/', 'MessagingController@index')->name('messaging');
    Route::post('/api/get_list', 'MessagingController@api_get_list')->name('get_list_messaging');
    Route::post('/api/get_list_message', 'MessagingController@api_get_list_message')->name('get_list_messaging_message');
    Route::get('/api/create_room', 'MessagingController@api_create_room')->name('create_room_messaging');
    Route::post('/api/sent_message', 'MessagingController@api_store_message')->name('store_message_messaging');
    Route::post('/api/confirm_action', 'MessagingController@api_set_action')->name('set_action_messaging');
    Route::post('/api/set_as_read', 'MessagingController@api_set_read')->name('set_read_messaging');
    Route::get('/download/{id}','MessagingController@download')->name('download_messaging');
});

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('rating')->group(function () {
    Route::get('/', 'RatingController@index')->name('rating');
});

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('log')->group(function () {
    Route::get('/', 'LogController@index')->name('log');
});

Route::middleware(['auth:sanctum', 'verified', 'associated', 'referenced', 'approved'])->prefix('member')->group(function () {
    Route::get('/', 'MemberController@index')->name('member');
    Route::get('/add', 'MemberController@add')->name('member.add');
    Route::post('/add', 'MemberController@save')->name('member.save');
    Route::delete('/{hash}', 'MemberController@delete')->name('member.delete');
});

Route::get('/print/nego/{hash}','NegoController@print')->name('print_nego');
Route::get('/print/complaint/{hash}','ComplaintController@print')->name('print_complaint');
Route::get('/print/order/po/{hash}','OrderController@print_po')->name('print_po');
Route::get('/print/order/bast/{hash}','OrderController@print_bast')->name('print_bast');
Route::get('/print/order/invoice/{hash}','OrderController@print_invoice')->name('print_invoice');
