<?php

namespace JPeters\Architect\TestHelpers\Laravel\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Foundation\Mix;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class TestingServiceProvider extends ServiceProvider
{
    public function register()
    {
        Config::set('app.key', Str::random(32));
        $this->app->instance(Mix::class, new \JPeters\Architect\TestHelpers\Laravel\Mix());
    }
}
