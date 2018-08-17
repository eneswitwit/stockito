<?php

namespace App\Providers;

use App\Events\CreativeJoinedToBrandEvent;
use App\Events\DeletedFileEvent;
use App\Events\EditedFileEvent;
use App\Events\LicenseChangedForMediaEvent;
use App\Events\UploadedFileEvent;
use App\Listeners\CreateNewActivityListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        CreativeJoinedToBrandEvent::class => [
            CreateNewActivityListener::class
        ],
        LicenseChangedForMediaEvent::class => [
            CreateNewActivityListener::class
        ],
        UploadedFileEvent::class => [
            CreateNewActivityListener::class
        ],
        DeletedFileEvent::class => [
            CreateNewActivityListener::class
        ],
        EditedFileEvent::class => [
            CreateNewActivityListener::class
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\UpdateLastLoginListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
