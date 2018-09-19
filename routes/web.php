<?php

/** Admin */
Route::get('/admin/login', ['as' => 'admin.login', 'uses' => 'AuthAdmin\LoginController@showLoginForm']);
Route::post('/admin/login', ['uses' => 'AuthAdmin\LoginController@login']);
Route::get('/admin/logout', ['as' => 'admin.logout', 'uses' => 'AuthAdmin\LoginController@logout']);

/** Webhook Stripe */
Route::post('webhooks/stripe', ['as' => 'webhooks.stripe', 'uses' => 'Webhooks\StripeController@index']);

/** Files */
Route::get('image/{name}', 'FileController@image')->name('image');
Route::get('image-thumbnail/{name}', 'FileController@imageThumbnail')->name('image-thumbnail');
Route::get('license/{name}', 'FileController@license')->name('license');
Route::get('files/download/{hash}', 'FileController@downloadZip')->name('download');
Route::get('files/invoices/{invoice}/download', 'FileController@downloadInvoicePdf')->name('download.invoice.pdf');
Route::get('share/image/{hash}', 'FileController@shareImage')->name('share.image');

/** Landingpage */
Route::get('', 'IndexController@landingpage');

/** API/Admin/Files/Storage */
Route::get('{path}', function () {
    return view('index');
})->where('path', '^(?!admin|files|api|storage)(.*)');