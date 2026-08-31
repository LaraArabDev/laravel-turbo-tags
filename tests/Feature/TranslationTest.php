<?php

use LaraArabDev\TurboTags\Models\Tag;

it('can get translated name for a specific locale', function () {
    $tag = Tag::create([
        'name' => ['en' => 'Technology', 'ar' => 'تقنية'],
        'slug' => 'technology',
    ]);

    expect($tag->getTranslatedName('en'))->toBe('Technology')
        ->and($tag->getTranslatedName('ar'))->toBe('تقنية');
});

it('falls back to primary locale', function () {
    config(['laravel-turbo-tags.locale.primary' => 'en']);

    $tag = Tag::create([
        'name' => ['en' => 'Technology', 'ar' => 'تقنية'],
        'slug' => 'technology',
    ]);

    expect($tag->getTranslatedName('fr'))->toBe('Technology');
});

it('falls back to app locale', function () {
    app()->setLocale('ar');

    $tag = Tag::create([
        'name' => ['en' => 'Technology', 'ar' => 'تقنية'],
        'slug' => 'technology',
    ]);

    expect($tag->getTranslatedName())->toBe('تقنية');
});

it('falls back to first available translation', function () {
    $tag = Tag::create([
        'name' => ['fr' => 'Technologie'],
        'slug' => 'tech',
    ]);

    expect($tag->getTranslatedName('de'))->toBe('Technologie');
});

it('returns empty string for empty translations', function () {
    $tag = Tag::create([
        'name' => [],
        'slug' => 'empty',
    ]);

    expect($tag->getTranslatedName())->toBe('');
});

it('can set translated name', function () {
    $tag = Tag::create([
        'name' => ['en' => 'Original'],
        'slug' => 'original',
    ]);

    $tag->setTranslatedName('ترجمة', 'ar');
    $tag->save();
    $tag->refresh();

    expect($tag->getTranslatedName('en'))->toBe('Original')
        ->and($tag->getTranslatedName('ar'))->toBe('ترجمة');
});

it('can get all translations', function () {
    $tag = Tag::create([
        'name' => ['en' => 'Hello', 'ar' => 'مرحبا', 'fr' => 'Bonjour'],
        'slug' => 'hello',
    ]);

    expect($tag->getTranslations())->toHaveCount(3)
        ->and($tag->getTranslations())->toBe(['en' => 'Hello', 'ar' => 'مرحبا', 'fr' => 'Bonjour']);
});

it('can check if translation exists', function () {
    $tag = Tag::create([
        'name' => ['en' => 'Test', 'ar' => 'اختبار'],
        'slug' => 'test',
    ]);

    expect($tag->hasTranslation('en'))->toBeTrue()
        ->and($tag->hasTranslation('ar'))->toBeTrue()
        ->and($tag->hasTranslation('fr'))->toBeFalse();
});

it('falls back to config fallback locale', function () {
    config([
        'laravel-turbo-tags.locale.primary' => 'de',
        'laravel-turbo-tags.locale.fallback' => 'ar',
    ]);

    $tag = Tag::create([
        'name' => ['en' => 'Technology', 'ar' => 'تقنية'],
        'slug' => 'technology',
    ]);

    // de is primary but doesn't exist, app locale is en but primary was set to de
    // so it should fall to app locale (en), then fallback (ar)
    expect($tag->getTranslatedName('fr'))->toBe('Technology');
});

it('falls back to config fallback locale when primary and app locale both miss', function () {
    config([
        'laravel-turbo-tags.locale.primary' => 'de',
        'laravel-turbo-tags.locale.fallback' => 'ar',
    ]);

    app()->setLocale('de');

    $tag = Tag::create([
        'name' => ['ar' => 'تقنية', 'fr' => 'Technologie'],
        'slug' => 'tech',
    ]);

    // de (primary + app) doesn't exist, should fall to fallback (ar)
    expect($tag->getTranslatedName('ja'))->toBe('تقنية');
});

it('sets translated name using default locale when no locale given', function () {
    config(['laravel-turbo-tags.locale.primary' => 'ar']);

    $tag = Tag::create(['name' => ['en' => 'Test'], 'slug' => 'test']);

    $tag->setTranslatedName('اختبار');
    $tag->save();
    $tag->refresh();

    expect($tag->getTranslatedName('ar'))->toBe('اختبار')
        ->and($tag->getTranslatedName('en'))->toBe('Test');
});

it('sets translated name using app locale when no primary configured', function () {
    config(['laravel-turbo-tags.locale.primary' => null]);
    app()->setLocale('fr');

    $tag = Tag::create(['name' => ['en' => 'Test'], 'slug' => 'test']);

    $tag->setTranslatedName('Essai');
    $tag->save();
    $tag->refresh();

    expect($tag->getTranslatedName('fr'))->toBe('Essai');
});
