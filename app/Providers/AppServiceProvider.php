<?php

namespace App\Providers;

use App\Managers\Processors\AIFileProcessor;
use App\Managers\Processors\EPSFileProcessor;
use App\Managers\Processors\ImageFileProcessor;
use App\Managers\Processors\Mp4FileProcessor;
use App\Managers\Uploader\FTPUploader;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

/**
 * Class AppServiceProvider
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if ($this->app->runningUnitTests()) {
            Schema::defaultStringLength(191);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        Stripe::setApiKey(config('services.stripe.key'));

        $this->app->bind(App\Repositories\Contacts\VoucherRepositoryInterface::class,
            App\Repositories\VoucherRepository::class);

        $this->app->bind(App\Repositories\Contacts\ProductRepositoryInterface::class,
            App\Repositories\ProductRepository::class);

        $this->app->bind(ImageFileProcessor::class, ImageFileProcessor::class);
        $this->app->bind(Mp4FileProcessor::class, Mp4FileProcessor::class);
        $this->app->bind(AIFileProcessor::class, AIFileProcessor::class);
        $this->app->bind(EPSFileProcessor::class, EPSFileProcessor::class);
    }
}
