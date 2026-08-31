<?php

namespace LaraArabDev\:package_namespace;

use Illuminate\Support\ServiceProvider;

class :package_nameServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/:package_slug.php',
            ':package_slug',
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/:package_slug.php' => config_path(':package_slug.php'),
            ], ':package_slug-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], ':package_slug-migrations');
        }
    }
}
