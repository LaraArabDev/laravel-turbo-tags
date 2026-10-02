<?php

use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\Tests\Fixtures\TestModel;

it('defaults is_active to true', function () {
    $tag = Tag::create(['name' => ['en' => 'Active Tag'], 'slug' => 'active-tag']);

    expect($tag->is_active)->toBeTrue();
});

it('can create a tag with is_active false', function () {
    $tag = Tag::create(['name' => ['en' => 'Inactive Tag'], 'slug' => 'inactive-tag', 'is_active' => false]);

    expect($tag->is_active)->toBeFalse();
});

it('can scope active tags', function () {
    Tag::create(['name' => ['en' => 'Active'], 'slug' => 'active', 'is_active' => true]);
    Tag::create(['name' => ['en' => 'Inactive'], 'slug' => 'inactive', 'is_active' => false]);

    $active = Tag::active()->get();

    expect($active)->toHaveCount(1)
        ->and($active->first()->slug)->toBe('active');
});

it('can scope inactive tags', function () {
    Tag::create(['name' => ['en' => 'Active'], 'slug' => 'active', 'is_active' => true]);
    Tag::create(['name' => ['en' => 'Inactive'], 'slug' => 'inactive', 'is_active' => false]);

    $inactive = Tag::inactive()->get();

    expect($inactive)->toHaveCount(1)
        ->and($inactive->first()->slug)->toBe('inactive');
});

it('excludes inactive tags from findOrCreate lookup', function () {
    $inactive = Tag::create(['name' => ['en' => 'Laravel'], 'slug' => 'laravel', 'is_active' => false]);

    $found = Tag::findOrCreate('Laravel', null, 'en');

    expect($found->id)->not->toBe($inactive->id)
        ->and($found->is_active)->toBeTrue();
});

it('excludes inactive tags from findOrCreateMany lookup', function () {
    Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'is_active' => false]);

    $tags = Tag::findOrCreateMany(['PHP', 'Laravel'], null, 'en');

    expect($tags)->toHaveCount(2);

    $phpTag = $tags->first(fn (Tag $t) => $t->getTranslatedName('en') === 'PHP');

    expect($phpTag->is_active)->toBeTrue();
    expect(Tag::where('name->en', 'PHP')->count())->toBe(2);
});

it('excludes inactive tags from suggestions', function () {
    Tag::create(['name' => ['en' => 'Laravel'], 'slug' => 'laravel', 'is_active' => true]);
    Tag::create(['name' => ['en' => 'Lumen'], 'slug' => 'lumen', 'is_active' => false]);

    $suggestions = Tag::suggestions('L', null, 'en');
    expect($suggestions)->toHaveCount(0); // Below min_length

    $suggestions = Tag::suggestions('La', null, 'en');
    expect($suggestions)->toHaveCount(1)
        ->and($suggestions->first()->slug)->toBe('laravel');
});

it('excludes inactive tags from withAllTags scope', function () {
    $model = TestModel::create(['name' => 'Test']);

    $active = Tag::findOrCreate('Laravel', null, 'en');
    $inactive = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'is_active' => false]);

    $model->tags()->attach([$active->id, $inactive->id]);

    $results = TestModel::withAllTags(['Laravel', 'PHP'], null, 'en')->get();

    expect($results)->toHaveCount(0);
});

it('excludes inactive tags from withAnyTags scope', function () {
    $model = TestModel::create(['name' => 'Test']);

    $inactive = Tag::create(['name' => ['en' => 'Deprecated'], 'slug' => 'deprecated', 'is_active' => false]);
    $model->tags()->attach($inactive->id);

    $results = TestModel::withAnyTags(['Deprecated'], null, 'en')->get();

    expect($results)->toHaveCount(0);
});
