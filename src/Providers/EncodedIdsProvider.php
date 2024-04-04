<?php

namespace Davide7h\EncodedIds\Providers;

use Illuminate\Support\ServiceProvider;
use Davide7h\EncodedIds\Commands\InstallEncodedIds;

class EncodedIdsProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/encoded-ids.php' => config_path('encoded-ids.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallEncodedIds::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/encoded-ids.php', 'encoded-ids');
    }
}
