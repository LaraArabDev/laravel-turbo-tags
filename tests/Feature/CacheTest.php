<?php

use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\TagCache;

beforeEach(function () {
    config(['laravel-turbo-tags.cache.enabled' => true]);
    Tag::flushTagCache();
});

it('caches findOrCreate lookups', function () {
    $tag1 = Tag::findOrCreate('Laravel', null, 'en');
    $tag2 = Tag::findOrCreate('Laravel', null, 'en');

    expect($tag2->id)->toBe($tag1->id)
        ->and(Tag::count())->toBe(1);
});

it('invalidates cache on tag create', function () {
    Tag::findOrCreate('PHP', null, 'en');

    Tag::create(['name' => ['en' => 'New Tag'], 'slug' => 'new-tag']);

    $tag = Tag::findOrCreate('New Tag', null, 'en');

    expect($tag->getTranslatedName('en'))->toBe('New Tag')
        ->and(Tag::count())->toBe(2);
});

it('invalidates cache on tag update', function () {
    $tag = Tag::findOrCreate('Original', null, 'en');
    $tag->setTranslatedName('Updated', 'en');
    $tag->save();

    $fresh = Tag::findOrCreate('Updated', null, 'en');

    expect($fresh->id)->toBe($tag->id);
});

it('invalidates cache on tag delete', function () {
    $tag = Tag::findOrCreate('ToDelete', null, 'en');
    $tag->forceDelete();

    $newTag = Tag::findOrCreate('ToDelete', null, 'en');
    expect($newTag->id)->not->toBe($tag->id);
});

it('caches suggestions', function () {
    Tag::findOrCreate('Laravel Framework', null, 'en');
    Tag::findOrCreate('Lumen', null, 'en');

    $suggestions1 = Tag::suggestions('Lara', null, 'en');
    $suggestions2 = Tag::suggestions('Lara', null, 'en');

    expect($suggestions1)->toHaveCount(1)
        ->and($suggestions2)->toHaveCount(1);
});

it('caches allCached method', function () {
    Tag::findOrCreate('PHP', null, 'en');
    Tag::findOrCreate('Laravel', null, 'en');

    $all1 = Tag::allCached();
    $all2 = Tag::allCached();

    expect($all1)->toHaveCount(2)
        ->and($all2)->toHaveCount(2);
});

it('caches allOfTypeCached method', function () {
    Tag::findOrCreate('PHP', 'language', 'en');
    Tag::findOrCreate('Laravel', 'framework', 'en');

    $languages = Tag::allOfTypeCached('language');
    expect($languages)->toHaveCount(1)
        ->and($languages->first()->getTranslatedName('en'))->toBe('PHP');
});

it('flushTagCache clears all cached data', function () {
    Tag::findOrCreate('Cached', null, 'en');
    Tag::allCached();

    Tag::flushTagCache();

    $all = Tag::allCached();
    expect($all)->toHaveCount(1);
});

it('does not cache when disabled', function () {
    config(['laravel-turbo-tags.cache.enabled' => false]);

    $tag1 = Tag::findOrCreate('NoCache', null, 'en');
    $tag2 = Tag::findOrCreate('NoCache', null, 'en');

    expect($tag2->id)->toBe($tag1->id)
        ->and(Tag::count())->toBe(1);
});

it('respects custom cache store', function () {
    config(['laravel-turbo-tags.cache.store' => 'array']);

    $tag1 = Tag::findOrCreate('ArrayStore', null, 'en');
    $tag2 = Tag::findOrCreate('ArrayStore', null, 'en');

    expect($tag2->id)->toBe($tag1->id);
});

it('respects custom cache prefix', function () {
    config(['laravel-turbo-tags.cache.key_prefix' => 'my_tags']);

    Tag::findOrCreate('PrefixTest', null, 'en');

    $cacheKey = 'find.'.md5('PrefixTest||en');
    expect(TagCache::has($cacheKey))->toBeTrue();
});

it('invalidates cache on soft delete and restore', function () {
    $tag = Tag::findOrCreate('Restorable', null, 'en');
    $tag->delete();

    $tag->restore();

    $found = Tag::findOrCreate('Restorable', null, 'en');
    expect($found->id)->toBe($tag->id);
});

it('remembers a value from cache on second call', function () {
    $calls = 0;
    $result1 = TagCache::remember('test-key', function () use (&$calls) {
        $calls++;

        return 'computed-value';
    });

    $result2 = TagCache::remember('test-key', function () use (&$calls) {
        $calls++;

        return 'should-not-reach';
    });

    expect($result1)->toBe('computed-value')
        ->and($result2)->toBe('computed-value')
        ->and($calls)->toBe(1);
});

it('remember executes callback directly when cache disabled', function () {
    config(['laravel-turbo-tags.cache.enabled' => false]);

    $result = TagCache::remember('disabled-key', fn () => 'direct-value');

    expect($result)->toBe('direct-value');
});

it('can forget a specific cache key', function () {
    TagCache::put('forget-me', 'value');
    expect(TagCache::has('forget-me'))->toBeTrue();

    TagCache::forget('forget-me');
    expect(TagCache::has('forget-me'))->toBeFalse();
});

it('forget does nothing when cache disabled', function () {
    TagCache::put('keep-me', 'value');
    config(['laravel-turbo-tags.cache.enabled' => false]);

    TagCache::forget('keep-me');

    config(['laravel-turbo-tags.cache.enabled' => true]);
    expect(TagCache::has('keep-me'))->toBeTrue();
});
