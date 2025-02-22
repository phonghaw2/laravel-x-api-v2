<?php

namespace Phonghaw2\X;

use Illuminate\Support\ServiceProvider;
use Phonghaw2\X\Console\Adapter;

class XServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/x.php' => config_path('x.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/x.php', 'x');

        $this->app->singleton(Adapter::class);

        $this->app->singleton('twitter', function ($app) {
            return new X($app->make(Adapter::class));
        });
    }
}
