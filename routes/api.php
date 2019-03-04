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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('user', 'Auth\AuthController@user');

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/profile/creative', 'Settings\ProfileController@updateCreative');
    Route::delete('settings/profile/creative', 'Settings\ProfileController@deleteCreative');
    Route::patch('settings/password', 'Settings\PasswordController@update');
    Route::put('settings/profile/brand', 'Settings\ProfileController@updateBrand');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('register/brand', 'Auth\RegisterController@registerBrand');
    Route::post('register/creative', 'Auth\RegisterController@registerCreative');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::post('email/confirm', 'Auth\RegisterController@confirmEmail');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('', 'Api\ProductsController@index');
    Route::get('{product}', 'Api\ProductsController@show');
});

Route::group(['prefix' => 'plans'], function () {
    Route::get('all', 'Api\PlansController@index');
    Route::get('monthly', 'Api\PlansController@monthly');
    Route::get('{plan}', 'Api\PlansController@show');
});

Route::get('countries', 'Api\CountriesController@index');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('img-to-base64', 'Api\ToolsController@imgToBase64');

    Route::get('used-storage', 'Api\BrandController@getUsedStorage');

    Route::get('activities', 'Api\ActivitiesController@index');
    Route::get('cards', 'Api\CardController@index');
    Route::group(['prefix' => 'licenses'], function () {
        Route::get('types', 'Api\LicensesController@types');
        Route::get('types-long', 'Api\LicensesController@typesLong');
        Route::get('soon-expiring', 'Api\LicensesController@soonExpiring');
        Route::get('export/{brandId?}', 'Api\LicensesController@exportToPdf');
        Route::get('{brandId?}', 'Api\LicensesController@index');
    });
    Route::get('brands', 'Api\CreativeController@getCreativeBrands');
    Route::get('brands/{brand}/people-attributes', 'Api\BrandController@getPeopleAttributes');
    Route::get('brands/{brand}/suppliers', 'Api\BrandController@getSuppliers');
    Route::get('brands/{brand}/categories', 'Api\BrandController@getCategories');

    Route::group(['prefix' => 'brand'], function () {
        Route::get('{brandId?}/creatives', 'Api\BrandController@getBrandCreatives');
        Route::get('creatives', 'Api\BrandController@getBrandCreatives');
        Route::get('invoices', 'Api\BrandController@getBrandInvoices');
        Route::get('creatives/{id}', 'Api\BrandController@getBrandCreative');
        Route::post('{brandId?}/invite-creative', 'Api\BrandController@inviteCreative');
        Route::post('invite-creative', 'Api\BrandController@inviteCreative');
        Route::post('edit-creative', 'Api\BrandController@editCreative');
    });


    Route::group(['prefix' => 'creative'], function () {
        Route::delete('brand/{id}', 'Api\CreativeController@removeCreativeBrand');
    });

    Route::group(['prefix' => 'medias'], function () {

        Route::get('processing/{brandId?}', 'Api\MediaController@processing');
        Route::get('types', 'Api\MediaController@types');
        Route::delete('remove-multiple', 'Api\MediaController@removeMultiple');

        Route::group(['prefix' => '{media}'], function () {
            Route::post('submit', 'Api\MediaController@submit');
            Route::get('download', 'Api\MediaController@download');
            Route::post('add-license', 'Api\MediaController@addLicense');
            Route::get('', 'Api\MediaController@show');
            Route::put('', 'Api\MediaController@update');
            Route::delete('', 'Api\MediaController@remove');
        });

        Route::get('get/{id}', 'Api\MediaController@show');
        Route::get('get-multiple', 'Api\MediaController@getMultiple');
        Route::get('categories', 'Api\MediaController@categories');

        Route::get('uploads/{brandId?}', 'Api\MediaController@uploads');
        Route::get('uploads/step/{taken}/{toTake}/{brandId?}', 'Api\MediaController@getUploads');

        Route::get('', 'Api\MediaController@index');
        Route::get('{taken}/{toTake}', 'Api\MediaController@indexStep');
        Route::get('/brand/{brand_id}', 'Api\MediaController@getBrandMedias');
        Route::get('/brand/{taken}/{toTake}/{brand_id}', 'Api\MediaController@getBrandMediasStep');
        Route::post('upload', 'Api\MediaController@upload');
        Route::post('image/{name}', 'Api\MediaController@image');
        Route::post('share', 'Api\MediaController@share');
        Route::post('share-to-email', 'Api\MediaController@shareEmail');
        Route::get('download-multiple', 'Api\MediaController@downloadMultiple');
        Route::post('submit-multiple', 'Api\MediaController@submitMultiple');


    });

    Route::group(['prefix' => 'subscription'], function () {
        Route::get('tax', 'Api\SubscriptionController@getTaxPercentage');
        Route::post('pay-subscription', 'Api\SubscriptionController@paySubscription');
        Route::post('downgrade-subscription', 'Api\SubscriptionController@downgradeSubscription');
        Route::post('upgrade', 'Api\SubscriptionController@upgrade');
        Route::post('downgrade', 'Api\SubscriptionController@downgrade');
        Route::post('cancel-subscription', 'Api\SubscriptionController@cancelSubscription');
        Route::post('resume-subscription', 'Api\SubscriptionController@resumeSubscription');
    });

    Route::group(['prefix' => 'licenses'], function () {
        Route::get('{license}', 'Api\LicensesController@show');
        Route::put('{license}', 'Api\LicensesController@update');
        Route::post('', 'Api\LicensesController@create');
    });

    Route::group(['prefix' => 'ftp'], function () {
        Route::get('{email}/{brand_id?}', 'Api\FtpController@show');
    });

    Route::group(['prefix' => 'usage-license'], function () {
        Route::post('', 'UsageLicenseController@create');
    });
});
