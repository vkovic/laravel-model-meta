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

        // Load user factory
        $this->withFactories(__DIR__ . '/../tests/database/factories');

        // Load vkovic/laravel-meta migrations
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/vkovic/laravel-meta/src/database/migrations');

        // Load package migrations
        $this->packageMigrations();

        // Load testing support migrations
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/laravel/framework/tests/Database/migrations/one/2016_01_01_000000_create_users_table.php');
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