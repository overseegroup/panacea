<?php

namespace NotificationChannels\Panacea;

use Illuminate\Support\ServiceProvider;

class PanaceaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PanaceaApi::class, function ($app) {
            $config = config('services.panacea');

            return new PanaceaApi($config['login'], $config['secret'], $config['sender']);
        });
    }
}
