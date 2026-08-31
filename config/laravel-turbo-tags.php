<?php

use LaraArabDev\TurboTags\Models\Tag;

return [

    /*
    |--------------------------------------------------------------------------
    | Tag Model
    |--------------------------------------------------------------------------
    |
    | The model used for tags. You may extend the default Tag model and
    | specify your custom class here.
    |
    */

    'tag_model' => Tag::class,

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | The database table names used by TurboTags.
    |
    */

    'tables' => [
        'tags' => 'tags',
        'taggables' => 'taggables',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale Settings
    |--------------------------------------------------------------------------
    |
    | Configure the primary and fallback locales for tag name translations.
    | When null, the application locale will be used.
    |
    */

    'locale' => [
        'primary' => null,
        'fallback' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Slug Generation
    |--------------------------------------------------------------------------
    |
    | Configure how tag slugs are generated.
    |
    */

    'slugger' => [
        'source' => 'name',
        'generate_on_create' => true,
        'generate_unique' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configure tag caching behavior.
    |
    */

    'cache' => [
        'enabled' => false,
        'ttl' => 3600,
        'store' => null,
        'key_prefix' => 'turbo_tags',
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance
    |--------------------------------------------------------------------------
    |
    | Tune performance-related settings.
    |
    */

    'performance' => [
        'chunk_size' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Suggestions
    |--------------------------------------------------------------------------
    |
    | Configure tag suggestion behavior.
    |
    */

    'suggestions' => [
        'limit' => 10,
        'min_length' => 2,
    ],

];
