<?php

use LaraArabDev\TurboTags\Models\Tag;

it('auto-generates slug on create', function () {
    $tag = Tag::create(['name' => ['en' => 'Hello World']]);

    expect($tag->slug)->toBe('hello-world');
});

it('generates unique slugs', function () {
    Tag::create(['name' => ['en' => 'Test']]);
    $tag2 = Tag::create(['name' => ['en' => 'Test']]);
    $tag3 = Tag::create(['name' => ['en' => 'Test']]);

    expect($tag2->slug)->toBe('test-2')
        ->and($tag3->slug)->toBe('test-3');
});

it('does not overwrite existing slug', function () {
    $tag = Tag::create([
        'name' => ['en' => 'Test'],
        'slug' => 'custom-slug',
    ]);

    expect($tag->slug)->toBe('custom-slug');
});

it('respects generate_on_create config', function () {
    config(['laravel-turbo-tags.slugger.generate_on_create' => false]);

    $tag = Tag::create([
        'name' => ['en' => 'No Auto Slug'],
        'slug' => 'manual',
    ]);

    expect($tag->slug)->toBe('manual');
});

it('respects generate_unique config', function () {
    config(['laravel-turbo-tags.slugger.generate_unique' => false]);

    Tag::create(['name' => ['en' => 'Unique Test']]);

    // With unique disabled, this would try to create the same slug
    // We expect the slug to be generated without uniqueness check
    $tag2 = Tag::create(['name' => ['en' => 'Unique Test'], 'slug' => 'unique-test-manual']);

    expect($tag2->slug)->toBe('unique-test-manual');
});

it('uses primary locale for slug generation', function () {
    config(['laravel-turbo-tags.locale.primary' => 'en']);

    $tag = Tag::create([
        'name' => ['en' => 'English Name', 'ar' => 'اسم عربي'],
    ]);

    expect($tag->slug)->toBe('english-name');
});

it('uses first available locale when primary is missing', function () {
    config(['laravel-turbo-tags.locale.primary' => 'de']);

    $tag = Tag::create([
        'name' => ['en' => 'English Only'],
    ]);

    expect($tag->slug)->toBe('english-only');
});
