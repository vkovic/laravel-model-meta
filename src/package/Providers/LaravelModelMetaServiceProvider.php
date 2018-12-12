<?php

namespace Vkovic\LaravelModelMeta\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelModelMetaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
