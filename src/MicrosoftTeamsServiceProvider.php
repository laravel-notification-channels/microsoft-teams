<?php

namespace NotificationChannels\MicrosoftTeams;

use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class MicrosoftTeamsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.

        $this->app->when(MicrosoftTeamsChannel::class)
            ->needs(MicrosoftTeams::class)
            ->give(function (Container $app) {
                return new MicrosoftTeams(
                    $app->make(Factory::class)
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        Notification::extend('microsoftTeams', function (Container $app) {
            return $app->make(MicrosoftTeamsChannel::class);
        });
    }
}
