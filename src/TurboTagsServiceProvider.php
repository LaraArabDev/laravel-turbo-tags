<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the TurboTags package.
 *
 * Registers configuration and publishes config and migration files.
 */
class TurboTagsServiceProvider extends ServiceProvider
{
    /**
     * Register the package configuration.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-turbo-tags.php',
            'laravel-turbo-tags',
        );
    }

    /**
     * Bootstrap publishable assets.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel-turbo-tags.php' => config_path('laravel-turbo-tags.php'),
            ], 'laravel-turbo-tags-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'laravel-turbo-tags-migrations');
        }
    }
}
