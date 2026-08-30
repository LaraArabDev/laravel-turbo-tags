<?php

use LaraArabDev\TurboTags\Models\TurboTag;

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Customize the models used by TurboTags. You can extend the default
    | models and register your custom classes here.
    |
    */

    'models' => [
        'tag' => TurboTag::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | Customize the database table names used by TurboTags.
    |
    */

    'tables' => [
        'tags' => 'turbo_tags',
        'taggables' => 'turbo_taggables',
        'tag_slugs' => 'turbo_tag_slugs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Scope
    |--------------------------------------------------------------------------
    |
    | The default scope applied when creating tags without specifying a scope.
    | Supported: "global", "team", "personal"
    |
    */

    'default_scope' => 'global',

    /*
    |--------------------------------------------------------------------------
    | Scope Resolution Order
    |--------------------------------------------------------------------------
    |
    | When resolving tags by name, this defines the order in which scopes
    | are searched. The first match wins.
    |
    */

    'scope_resolution' => ['personal', 'team', 'global'],

    /*
    |--------------------------------------------------------------------------
    | Auto-Create Scope
    |--------------------------------------------------------------------------
    |
    | When attaching a tag by name and no match is found, automatically create
    | a new tag in this scope. Set to null to throw an exception instead.
    |
    */

    'auto_create_scope' => null,

    /*
    |--------------------------------------------------------------------------
    | Slug Configuration
    |--------------------------------------------------------------------------
    |
    | Configure localized slug generation and slug history tracking.
    |
    */

    'slug' => [
        'enabled' => true,
        'history' => true,
        'separator' => '-',
        'unique_scope' => 'type',
        'generator' => null,
        'max_length' => 255,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the tag caching layer. Set enabled to false to disable caching.
    |
    */

    'cache' => [
        'enabled' => true,
        'store' => null,
        'ttl' => 3600,
        'prefix' => 'turbo_tags',
        'model_tags' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Counters
    |--------------------------------------------------------------------------
    |
    | Enable denormalized usage_count counters on tags. When enabled, attaching
    | or detaching a tag will atomically update the counter.
    |
    */

    'counters' => [
        'enabled' => true,
        'async' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale for tag translations. Set to null to use Laravel's
    | default fallback locale from config('app.fallback_locale').
    |
    */

    'fallback_locale' => null,

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    |
    | Enable soft deletes on tags. When disabled, tags are permanently deleted.
    |
    */

    'soft_deletes' => true,

];
