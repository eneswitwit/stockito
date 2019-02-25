<?php

//Route::get('', ['as' => 'admin.dashboard', function () {
//	$content = 'Define your dashboard here.';
//	return AdminSection::view($content, 'Dashboard');
//}]);
Route::get('', [
	'as' => 'admin.dashboard',
	'uses' => '\App\Http\Controllers\Admin\DashboardController@index',
]);


Route::get('customers/export', [
    'as' => 'admin.dashboard.export',
    'uses' => '\App\Http\Controllers\Admin\DashboardController@exportNewsletter',
]);

Route::get('invoices/{id}/show', '\App\Http\Controllers\Admin\FinanceController@show')->name('admin.invoices.show');
Route::get('invoices/{id}/download', '\App\Http\Controllers\Admin\FinanceController@download')->name('admin.invoices.download');

Route::get('customers/brand', [
	'as' => 'admin.customers.brand',
	'uses' => '\App\Http\Controllers\Admin\BrandController@index',
]);

Route::get('brand/datatables', [
	'as' => 'admin.brand.data',
	'uses' => '\App\Http\Controllers\Admin\BrandController@BrandsData',
]);

Route::get('customers/brand/{id}', [
	'as' => 'admin.brand.detail',
	'uses' => '\App\Http\Controllers\Admin\BrandController@BrandsDetail',
]);
Route::post('customers/brand/{id}', [
	'as' => 'admin.brand.edit',
	'uses' => '\App\Http\Controllers\Admin\BrandController@BrandsEdit',
]);

Route::get('customers/creative', [
	'as' => 'admin.customers.creative',
	'uses' => '\App\Http\Controllers\Admin\CreativeController@index'
]);

Route::get('creative/datatables', [
	'as' => 'admin.creative.data',
	'uses' => '\App\Http\Controllers\Admin\CreativeController@CreativesData'
]);

Route::get('customers/creative/{id}', [
	'as' => 'admin.creative.detail',
	'uses' => '\App\Http\Controllers\Admin\CreativeController@CreativeDetail'
]);

Route::get('customers/creative/{id}/remove_brand/{brand_id}', [
	'as' => 'admin.creative.remove_brand',
	'uses' => '\App\Http\Controllers\Admin\CreativeController@RemoveBrand'
]);

Route::post('customers/creative/{id}', [
	'as' => 'admin.creative.edit',
	'uses' => '\App\Http\Controllers\Admin\CreativeController@CreativeEdit'
]);

Route::get('product', [
    'as' => 'admin.product',
    'uses' => '\App\Http\Controllers\Admin\ProductController@index',
]);

Route::get('product/datatables', [
	'as' => 'admin.product.data',
	'uses' => '\App\Http\Controllers\Admin\ProductController@PlansData',
]);

Route::get('product/{id}', [
	'as' => 'admin.product.id',
	'uses' => '\App\Http\Controllers\Admin\ProductController@getPlan',
]);
Route::post('product/new', [
	'as' => 'admin.product.new',
	'uses' => '\App\Http\Controllers\Admin\ProductController@addPlan',
]);

Route::get('product/{id}/delete', [
	'as' => 'admin.product.delete',
	'uses' => '\App\Http\Controllers\Admin\ProductController@deletePlan',
]);

Route::post('product/{id}', [
	'as' => 'admin.product.edit',
	'uses' => '\App\Http\Controllers\Admin\ProductController@editPlan',
]);
Route::get('vouchers/datatables', [
	'as' => 'admin.voucher.data',
	'uses' => '\App\Http\Controllers\Admin\ProductController@VouchersData',
]);

Route::get('voucher/{id}/delete', [
    'as' => 'admin.voucher.delete',
    'uses' => '\App\Http\Controllers\Admin\ProductController@VouchersDelete',
]);

Route::get('voucher/{id}', [
	'as' => 'admin.voucher.id',
	'uses' => '\App\Http\Controllers\Admin\ProductController@getVoucher',
]);
Route::post('voucher/new', [
	'as' => 'admin.voucher.add',
	'uses' => '\App\Http\Controllers\Admin\ProductController@addVoucher',
]);

Route::post('voucher/{id}', [
	'as' => 'admin.voucher.edit',
	'uses' => '\App\Http\Controllers\Admin\ProductController@editVoucher',
]);

Route::get('finance', [
	'as' => 'admin.finance',
	'uses' => '\App\Http\Controllers\Admin\FinanceController@index',
]);
Route::get('finance/datatables', [
    'as' => 'admin.finance.data',
    'uses' => '\App\Http\Controllers\Admin\FinanceController@FinanceData',
]);

Route::get('media-files', [
	'as' => 'admin.media.files',
	'uses' => '\App\Http\Controllers\Admin\MediaFilesController@index',
]);

Route::post('download/{id}', [
	'as' => 'admin.media.download',
	'uses' => '\App\Http\Controllers\Admin\MediaFilesController@image',
]);

Route::get('media-files/datatables', [
	'as' => 'admin.media.data',
	'uses' => '\App\Http\Controllers\Admin\MediaFilesController@MediasData',
]);

