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
        // Migrations
        //

        // Load migrations defined in service provider
        $this->artisan('migrate');

        // Load testing migrations
        $this->loadMigrationsFrom(__DIR__ . '/../tests/database/migrations');

        //
        // Factories
        //

        // Load user factory
        $this->withFactories(__DIR__ . '/../tests/database/factories');
    }

    /**
     * Get package providers
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        // This will also load migrations defined in the service provider
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