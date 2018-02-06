<?php

namespace KIPR\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'KIPR\Events\Event' => [
            'KIPR\Listeners\EventListener',
        ],
        'App\Events\MatchScored' => [
            'App\Listeners\UpdateBracket',
        ],
        'App\Events\MatchReady' => [],
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
