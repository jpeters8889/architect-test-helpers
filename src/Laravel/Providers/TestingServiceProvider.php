<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Laravel\Providers;

use Illuminate\Support\Str;
use Illuminate\Foundation\Mix;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Config;

class TestingServiceProvider extends ServiceProvider
{
    public function register()
    {
        Config::set('app.key', Str::random(32));
        $this->app->instance(Mix::class, new \JPeters\Architect\TestHelpers\Laravel\Mix());
    }
}
