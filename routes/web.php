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

Route::get('/admin/login',['as' => 'admin.login','uses' => 'AuthAdmin\LoginController@showLoginForm']);
Route::post('/admin/login',['uses' => 'AuthAdmin\LoginController@login']);
Route::get('/admin/logout',['as' => 'admin.logout','uses' => 'AuthAdmin\LoginController@logout']);
Route::post('webhooks/stripe', ['as' => 'webhooks.stripe','uses' => 'Webhooks\StripeController@index']);
Route::get('image/{name}', 'FileController@image')->name('image');
Route::get('image-thumbnail/{name}', 'FileController@imageThumbnail')->name('image-thumbnail');
Route::get('license/{name}', 'FileController@license')->name('license');
Route::get('files/download/{hash}', 'FileController@downloadZip')->name('download');
Route::get('files/invoices/{invoice}/download', 'FileController@downloadInvoicePdf')->name('download.invoice.pdf');

Route::get('share/image/{hash}', 'FileController@shareImage')->name('share.image');

Route::get('{path}', function () {
    return view('index');
})->where('path', '^(?!admin|files|api|storage)(.*)');