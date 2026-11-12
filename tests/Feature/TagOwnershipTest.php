<?php

use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\Tests\Fixtures\TestModel;
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

// ── createTag / createTags ────────────────────────────────────────────────────

it('createTag creates a tag owned by the calling model and attaches it', function () {
    $model = TestModel::create(['name' => 'Post One']);

    $model->createTag('exclusive');

    $model->refresh();

    expect($model->tags)->toHaveCount(1);

    $tag = $model->tags->first();
    expect($tag->name)->toBe('exclusive');
    expect($tag->owner_type)->toBe(TestModel::class);
    expect($tag->owner_id)->toBe($model->getKey());
});

it('createTag does not create a duplicate when called twice with the same name', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->createTag('vip');
    $model->createTag('vip');

    expect(Tag::count())->toBe(1);
    expect($model->tags()->count())->toBe(1);
});

it('createTag accepts an array of names', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->createTag(['alpha', 'beta', 'gamma']);

    expect($model->tags()->count())->toBe(3);
    expect(Tag::where('owner_type', TestModel::class)->where('owner_id', $model->getKey())->count())->toBe(3);
});

it('createTags is an alias for createTag', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->createTags(['one', 'two']);

    expect($model->tags()->count())->toBe(2);
});

it('createTag isolates tags between different model instances', function () {
    $model1 = TestModel::create(['name' => 'Post A']);
    $model2 = TestModel::create(['name' => 'Post B']);

    $model1->createTag('shared-name');
    $model2->createTag('shared-name');

    // Two separate owned tags exist — not the same record
    expect(Tag::count())->toBe(2);

    $tag1 = $model1->tags->first();
    $tag2 = $model2->tags->first();
    expect($tag1->id)->not->toBe($tag2->id);
    expect($tag1->owner_id)->toBe($model1->getKey());
    expect($tag2->owner_id)->toBe($model2->getKey());
});

it('createTag respects type', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->createTag('php', 'skill');

    $tag = $model->tags->first();
    expect($tag->type)->toBe('skill');
    expect($tag->owner_type)->toBe(TestModel::class);
});

it('attachTag still creates global tags (no owner)', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->attachTag('global-tag');

    $tag = $model->tags->first();
    expect($tag->owner_type)->toBeNull();
    expect($tag->owner_id)->toBeNull();
});

it('createTag and attachTag can coexist on the same model', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->attachTag('public-tag');
    $model->createTag('private-tag');

    expect($model->tags()->count())->toBe(2);

    $owned = $model->tags->firstWhere('owner_type', TestModel::class);
    $global = $model->tags->firstWhere('owner_type', null);

    expect($owned->name)->toBe('private-tag');
    expect($global->name)->toBe('public-tag');
});
