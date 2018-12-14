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
        // This package depends on vkovic/laravel-meta package
        // and it's migration, so we'll load it here.
        // If user already have it installed, this migration will do nothing
        $this->loadMigrationsFrom(__DIR__ . '/../../../vendor/vkovic/laravel-meta/src/database/migrations');

        // Load this package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
