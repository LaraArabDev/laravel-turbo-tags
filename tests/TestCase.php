<?php

namespace LaraArabDev\TurboTags\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaraArabDev\TurboTags\TurboTagsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            TurboTagsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
    }

    protected function setUpDatabase(): void
    {
        $migration = include __DIR__.'/../database/migrations/create_turbo_tags_table.php';
        $migration->up();

        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
        });

        Schema::create('test_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
        });
    }
}
