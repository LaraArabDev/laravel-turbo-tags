<?php

use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\Tests\Fixtures\TagType;

it('can create a tag', function () {
    $tag = Tag::create(['name' => ['en' => 'Laravel'], 'slug' => 'laravel']);

    expect($tag)->toBeInstanceOf(Tag::class)
        ->and($tag->slug)->toBe('laravel')
        ->and($tag->name)->toBe(['en' => 'Laravel']);
});

it('can find or create a tag by name', function () {
    $tag = Tag::findOrCreate('Laravel', null, 'en');

    expect($tag->getTranslatedName('en'))->toBe('Laravel')
        ->and($tag->slug)->not->toBeEmpty();

    $found = Tag::findOrCreate('Laravel', null, 'en');
    expect($found->id)->toBe($tag->id);
});

it('can find or create many tags', function () {
    $tags = Tag::findOrCreateMany(['PHP', 'Laravel', 'Testing'], null, 'en');

    expect($tags)->toHaveCount(3);

    $tagsAgain = Tag::findOrCreateMany(['PHP', 'Laravel', 'New'], null, 'en');
    expect($tagsAgain)->toHaveCount(3);
    expect(Tag::count())->toBe(4);
});

it('can find or create with type', function () {
    $category = Tag::findOrCreate('PHP', 'category', 'en');
    $language = Tag::findOrCreate('PHP', 'language', 'en');

    expect($category->id)->not->toBe($language->id)
        ->and($category->type)->toBe('category')
        ->and($language->type)->toBe('language');
});

it('can scope by type', function () {
    Tag::findOrCreate('PHP', 'language', 'en');
    Tag::findOrCreate('Laravel', 'framework', 'en');
    Tag::findOrCreate('Python', 'language', 'en');

    expect(Tag::ofType('language')->count())->toBe(2)
        ->and(Tag::ofType('framework')->count())->toBe(1);
});

it('can scope containing search', function () {
    Tag::findOrCreate('Laravel Framework', null, 'en');
    Tag::findOrCreate('Lumen', null, 'en');

    expect(Tag::containing('Lara', 'en')->count())->toBe(1);
});

it('can scope ordered', function () {
    Tag::create(['name' => ['en' => 'B'], 'slug' => 'b', 'order_column' => 2]);
    Tag::create(['name' => ['en' => 'A'], 'slug' => 'a', 'order_column' => 1]);

    $tags = Tag::ordered()->get();

    expect($tags->first()->slug)->toBe('a')
        ->and($tags->last()->slug)->toBe('b');
});

it('can scope with slug', function () {
    Tag::create(['name' => ['en' => 'Test'], 'slug' => 'test-tag']);

    expect(Tag::withSlug('test-tag')->first())->not->toBeNull()
        ->and(Tag::withSlug('nonexistent')->first())->toBeNull();
});

it('can store and retrieve metadata', function () {
    $tag = Tag::create([
        'name' => ['en' => 'Test'],
        'slug' => 'test',
        'metadata' => ['color' => 'blue', 'icon' => 'star'],
    ]);

    $tag->refresh();

    expect($tag->metadata)->toBe(['color' => 'blue', 'icon' => 'star']);
});

it('can get suggestions', function () {
    Tag::findOrCreate('Laravel', null, 'en');
    Tag::findOrCreate('Lumen', null, 'en');
    Tag::findOrCreate('Livewire', null, 'en');

    $suggestions = Tag::suggestions('La', null, 'en');
    expect($suggestions)->toHaveCount(1);

    $suggestions = Tag::suggestions('L', null, 'en');
    expect($suggestions)->toHaveCount(0); // Below min_length
});

it('can find or create with enum type', function () {
    $tag = Tag::findOrCreate('PHP', TagType::Category, 'en');

    expect($tag->type)->toBe('category')
        ->and($tag->getTranslatedName('en'))->toBe('PHP');

    $same = Tag::findOrCreate('PHP', TagType::Category, 'en');
    expect($same->id)->toBe($tag->id);

    $different = Tag::findOrCreate('PHP', TagType::Label, 'en');
    expect($different->id)->not->toBe($tag->id)
        ->and($different->type)->toBe('label');
});

it('can scope by enum type', function () {
    Tag::findOrCreate('PHP', TagType::Category, 'en');
    Tag::findOrCreate('Laravel', TagType::Label, 'en');
    Tag::findOrCreate('Python', TagType::Category, 'en');

    expect(Tag::ofType(TagType::Category)->count())->toBe(2)
        ->and(Tag::ofType(TagType::Label)->count())->toBe(1);
});

it('can get suggestions with enum type', function () {
    Tag::findOrCreate('Laravel', TagType::Category, 'en');
    Tag::findOrCreate('Lumen', TagType::Label, 'en');

    $suggestions = Tag::suggestions('L', TagType::Category, 'en');
    expect($suggestions)->toHaveCount(0); // Below min_length

    $suggestions = Tag::suggestions('La', TagType::Category, 'en');
    expect($suggestions)->toHaveCount(1);
});

it('respects suggestions limit', function () {
    foreach (range(1, 15) as $i) {
        Tag::findOrCreate("Tag {$i}", null, 'en');
    }

    $suggestions = Tag::suggestions('Tag', null, 'en', 5);
    expect($suggestions)->toHaveCount(5);
});
