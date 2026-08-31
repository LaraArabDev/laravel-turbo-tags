<?php

use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\Tests\Fixtures\TestUser;

it('creates global tags with null owner', function () {
    $tag = Tag::create(['name' => ['en' => 'Global'], 'slug' => 'global']);

    expect($tag->owner_type)->toBeNull();
    expect($tag->owner_id)->toBeNull();
});

it('creates tags with an owner', function () {
    $user = TestUser::create(['name' => 'Salem']);

    $tag = Tag::create([
        'name' => ['en' => 'Custom'],
        'slug' => 'custom',
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    expect($tag->owner_type)->toBe(TestUser::class);
    expect($tag->owner_id)->toBe($user->getKey());
});

it('returns the owner via relationship', function () {
    $user = TestUser::create(['name' => 'Salem']);

    $tag = Tag::create([
        'name' => ['en' => 'Owned'],
        'slug' => 'owned',
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    expect($tag->owner)->toBeInstanceOf(TestUser::class);
    expect($tag->owner->getKey())->toBe($user->getKey());
});

it('scopes to global tags only', function () {
    $user = TestUser::create(['name' => 'Salem']);

    Tag::create(['name' => ['en' => 'Global One'], 'slug' => 'global-one']);
    Tag::create(['name' => ['en' => 'Global Two'], 'slug' => 'global-two']);
    Tag::create([
        'name' => ['en' => 'Custom'],
        'slug' => 'custom',
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    $globalTags = Tag::global()->get();

    expect($globalTags)->toHaveCount(2);
    expect($globalTags->pluck('slug')->all())->toBe(['global-one', 'global-two']);
});

it('scopes to tags owned by a specific model', function () {
    $user1 = TestUser::create(['name' => 'Salem']);
    $user2 = TestUser::create(['name' => 'Ali']);

    Tag::create(['name' => ['en' => 'Global'], 'slug' => 'global']);
    Tag::create([
        'name' => ['en' => 'User1 Tag'],
        'slug' => 'user1-tag',
        'owner_type' => $user1->getMorphClass(),
        'owner_id' => $user1->getKey(),
    ]);
    Tag::create([
        'name' => ['en' => 'User2 Tag'],
        'slug' => 'user2-tag',
        'owner_type' => $user2->getMorphClass(),
        'owner_id' => $user2->getKey(),
    ]);

    $user1Tags = Tag::ownedBy($user1)->get();

    expect($user1Tags)->toHaveCount(1);
    expect($user1Tags->first()->slug)->toBe('user1-tag');
});

it('scopes to tags available to a specific model', function () {
    $user1 = TestUser::create(['name' => 'Salem']);
    $user2 = TestUser::create(['name' => 'Ali']);

    Tag::create(['name' => ['en' => 'Global'], 'slug' => 'global']);
    Tag::create([
        'name' => ['en' => 'User1 Tag'],
        'slug' => 'user1-tag',
        'owner_type' => $user1->getMorphClass(),
        'owner_id' => $user1->getKey(),
    ]);
    Tag::create([
        'name' => ['en' => 'User2 Tag'],
        'slug' => 'user2-tag',
        'owner_type' => $user2->getMorphClass(),
        'owner_id' => $user2->getKey(),
    ]);

    $availableTags = Tag::availableTo($user1)->get();

    expect($availableTags)->toHaveCount(2);
    expect($availableTags->pluck('slug')->sort()->values()->all())->toBe(['global', 'user1-tag']);
});
