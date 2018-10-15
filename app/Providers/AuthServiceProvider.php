<?php

// namespace
namespace App\Providers;

// use
use App\Models\UsageLicense;
use App\Models\Media;
use App\Policies\License\UsageLicensePolicy;
use App\Policies\Media\MediaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Media::class => MediaPolicy::class,
        UsageLicense::class => UsageLicensePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
