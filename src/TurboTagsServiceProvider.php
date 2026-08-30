<?php

namespace LaraArabDev\TurboTags;

use Illuminate\Support\ServiceProvider;

class TurboTagsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/turbo-tags.php',
            'turbo-tags',
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/turbo-tags.php' => config_path('turbo-tags.php'),
            ], 'turbo-tags-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'turbo-tags-migrations');
        }
    }
}
