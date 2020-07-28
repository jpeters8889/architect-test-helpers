<?php

namespace JPeters\Architect\TestHelpers;

use Illuminate\Foundation\Application;
use JPeters\Architect\Architect;
use JPeters\Architect\Providers\ArchitectApplicationServiceProvider;
use JPeters\Architect\Providers\ArchitectServiceProvider;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Laravel\Providers\TestingServiceProvider;
use Orchestra\Testbench\TestCase;

class ArchitectTestCase extends TestCase
{
    /** @var Architect */
    protected $architect;

    /** @var Application */
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrations();

        $this->withFactories(__DIR__ . '/Laravel/Factories');

        $this->architect = resolve(Architect::class);
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path' => realpath(__DIR__ . '/Laravel/Migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ArchitectServiceProvider::class,
            ArchitectApplicationServiceProvider::class,
            TestingServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->useStoragePath('');
        $app->instance('path.public', __DIR__ . '/../../architect/public');

        $app['config']->set(['auth.providers.users.model' => User::class]);

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
