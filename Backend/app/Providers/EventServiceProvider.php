<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'Illuminate\Auth\Events\Login' => [
        'App\Listeners\UpdateLastLoginOnLogin@handle',
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
                // add your listeners (aka providers) here
                'SocialiteProviders\Spotify\SpotifyExtendSocialite@handle',
                'SocialiteProviders\SoundCloud\SoundCloudExtendSocialite@handle',
                'SocialiteProviders\YouTube\YouTubeExtendSocialite@handle',
                'SocialiteProviders\Instagram\InstagramExtendSocialite@handle',
            ], 
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
         require app_path('Http/events.php');

        //
    }
}
