<?php

namespace NotificationChannels\MicrosoftTeams;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Container\Container;
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
            ->give(static function () {
                return new MicrosoftTeams(
                    new HttpClient()
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Info: Name 'teams' is deprecated an will be removed with the next major release
        // Please use 'microsoftTeams' for all calls
        Notification::extend('teams', static function (Container $app) {
            return $app->make(MicrosoftTeamsChannel::class);
        });

        Notification::extend('microsoftTeams', static function (Container $app) {
            return $app->make(MicrosoftTeamsChannel::class);
        });
    }
}
