<?php

namespace Vkovic\LaravelModelMeta\Test;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Vkovic\LaravelModelMeta\Providers\LaravelModelMetaServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        //
        // Factories
        //

        // Load user factory
        $this->withFactories(__DIR__ . '/../tests/database/factories');

        //
        // Migrations
        //

        // Load vkovic/laravel-meta migrations
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/vkovic/laravel-meta/src/database/migrations');

        // Load this package migrations
        $this->packageMigrations();

        // Load testing migrations
        $this->loadMigrationsFrom(__DIR__ . '/../tests/database/migrations');
    }

    /**
     * Run default package migrations
     *
     * @return void
     */
    protected function packageMigrations()
    {
        $this->artisan('migrate');
    }

    /**
     * Get package providers
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [LaravelModelMetaServiceProvider::class];
    }

    /**
     * Define environment setup
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }
}