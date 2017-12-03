<?php

namespace NotificationChannels\Unisender\Providers;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Unisender\UnisenderApi;

class UnisenderChannelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(UnisenderApi::class, function ($app) {
            $token = $app['config']->get('services.unisender.api-key');

            return new UnisenderApi($token);
        });
    }
}
