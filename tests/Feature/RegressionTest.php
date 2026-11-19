<?php

/**
 * Regression tests for the five critical logic bugs fixed in this release.
 *
 * Bug 1 — Read ops (hasTag, withAllTags, etc.) must NEVER create tags as a side effect.
 * Bug 2 — Owner isolation: createTag must not leak across model instances.
 * Bug 3 — type=null must match only typeless tags (WHERE type IS NULL).
 * Bug 4 — findOrCreateMany with duplicate names must create exactly one record.
 * Bug 5 — tag_model config must be respected everywhere in HasTags.
 */

use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\Tests\Fixtures\TestModel;
use LaraArabDev\TurboTags\Tests\Fixtures\TestUser;

// ── Bug 1: Read operations must not create tags ───────────────────────────────

it('hasTag does not create a tag as a side effect', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->hasTag('ghost', null, 'en');

    expect(Tag::count())->toBe(0);
});

it('hasAllTags does not create tags as a side effect', function () {
    $model = TestModel::create(['name' => 'Post']);
    $model->attachTag('real', null, 'en');

    $model->hasAllTags(['real', 'ghost'], null, 'en');

    expect(Tag::where('name->en', 'ghost')->count())->toBe(0);
});

it('hasAnyTags does not create tags as a side effect', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->hasAnyTags(['phantom'], null, 'en');

    expect(Tag::count())->toBe(0);
});

it('scopeWithAllTags does not create tags as a side effect', function () {
    TestModel::create(['name' => 'Post']);

    TestModel::withAllTags(['shadow'], null, 'en')->get();

    expect(Tag::count())->toBe(0);
});

it('scopeWithAnyTags does not create tags as a side effect', function () {
    TestModel::create(['name' => 'Post']);

    TestModel::withAnyTags(['shadow'], null, 'en')->get();

    expect(Tag::count())->toBe(0);
});

it('detachTag does not create a tag as a side effect', function () {
    $model = TestModel::create(['name' => 'Post']);

    $model->detachTag('nonexistent', null, 'en');

    expect(Tag::count())->toBe(0);
});

it('hasAllTags returns false when any requested tag does not exist', function () {
    $model = TestModel::create(['name' => 'Post']);
    $model->attachTags(['a', 'b'], null, 'en');

    expect($model->hasAllTags(['a', 'b', 'missing'], null, 'en'))->toBeFalse();
});

it('withAllTags returns no results when any requested tag does not exist', function () {
    $model = TestModel::create(['name' => 'Post']);
    $model->attachTags(['a', 'b'], null, 'en');

    $results = TestModel::withAllTags(['a', 'b', 'missing'], null, 'en')->get();

    expect($results)->toHaveCount(0);
});

// ── Bug 3: type=null must match only typeless tags ────────────────────────────

it('findOrCreate with null type does not return a typed tag', function () {
    $typed = Tag::create(['name' => ['en' => 'php'], 'type' => 'skill']);

    $found = Tag::findOrCreate('php', null, 'en');

    // Must create a NEW typeless tag, not return the skill one
    expect($found->id)->not->toBe($typed->id);
    expect($found->type)->toBeNull();
});

it('findOrCreate with null type finds the typeless tag and not a typed one', function () {
    Tag::create(['name' => ['en' => 'php'], 'type' => 'skill']);
    $typeless = Tag::create(['name' => ['en' => 'php'], 'type' => null]);

    $found = Tag::findOrCreate('php', null, 'en');

    expect($found->id)->toBe($typeless->id);
});

it('findOrCreateMany with null type does not match typed tags', function () {
    Tag::create(['name' => ['en' => 'php'], 'type' => 'skill']);

    $tags = Tag::findOrCreateMany(['php'], null, 'en');

    expect($tags->first()->type)->toBeNull();
    expect(Tag::count())->toBe(2); // The existing skill tag + new typeless tag
});

it('attachTag with null type does not attach a typed tag', function () {
    $typed = Tag::create(['name' => ['en' => 'php'], 'type' => 'skill']);
    $model = TestModel::create(['name' => 'Post']);

    $model->attachTag('php', null, 'en');

    // Must have created and attached a typeless tag, not the skill one
    expect($model->tags()->count())->toBe(1);
    expect($model->tags->first()->id)->not->toBe($typed->id);
    expect($model->tags->first()->type)->toBeNull();
});

// ── Bug 4: findOrCreateMany with duplicate names ──────────────────────────────

it('findOrCreateMany with duplicate names creates exactly one tag', function () {
    $tags = Tag::findOrCreateMany(['php', 'php', 'php'], null, 'en');

    expect(Tag::count())->toBe(1);
    expect($tags)->toHaveCount(1);
});

it('findOrCreateMany with duplicate names returns consistent IDs', function () {
    $tags = Tag::findOrCreateMany(['alpha', 'beta', 'alpha'], null, 'en');

    expect(Tag::count())->toBe(2);
    // Both 'alpha' entries must resolve to the same tag
    $ids = $tags->pluck('id')->unique();
    expect($ids)->toHaveCount(2);
});

// ── Collection input branches ─────────────────────────────────────────────────

it('hasAllTags accepts a Collection of tag names', function () {
    $model = TestModel::create(['name' => 'Post']);
    $model->attachTags(['a', 'b'], null, 'en');

    $names = collect(['a', 'b']);

    expect($model->hasAllTags($names, null, 'en'))->toBeTrue();
});

it('scopeWithAllTags accepts a Collection of tag names', function () {
    $model = TestModel::create(['name' => 'Post']);
    $model->attachTags(['x', 'y'], null, 'en');

    $results = TestModel::withAllTags(collect(['x', 'y']), null, 'en')->get();

    expect($results)->toHaveCount(1);
});

// ── resolveLocale fallback paths ──────────────────────────────────────────────

it('resolveLocale uses config primary locale when no locale is passed', function () {
    config(['laravel-turbo-tags.locale.primary' => 'fr']);

    $model = TestModel::create(['name' => 'Post']);
    $tag = Tag::create(['name' => ['fr' => 'bonjour'], 'type' => null]);
    $model->tags()->attach($tag->id);

    // hasTag with no explicit locale should resolve via config primary ('fr')
    expect($model->hasTag('bonjour'))->toBeTrue();
});

it('resolveLocale falls back to app locale when config primary is not set', function () {
    config(['laravel-turbo-tags.locale.primary' => null]);
    app()->setLocale('de');

    $model = TestModel::create(['name' => 'Post']);
    $tag = Tag::create(['name' => ['de' => 'hallo'], 'type' => null]);
    $model->tags()->attach($tag->id);

    expect($model->hasTag('hallo'))->toBeTrue();
});

// ── Bug 6: Ownership leak — name-based lookups must only match global tags ────

it('findOrCreate does not return an owned tag with the same name', function () {
    $user = TestUser::create(['name' => 'Salem']);

    $owned = Tag::create([
        'name' => ['en' => 'vip'],
        'type' => null,
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    $found = Tag::findOrCreate('vip', null, 'en');

    expect($found->id)->not->toBe($owned->id);
    expect($found->owner_type)->toBeNull();
});

it('findOrCreateMany does not return owned tags', function () {
    $user = TestUser::create(['name' => 'Salem']);

    Tag::create([
        'name' => ['en' => 'vip'],
        'type' => null,
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    $tags = Tag::findOrCreateMany(['vip'], null, 'en');

    expect($tags)->toHaveCount(1);
    expect($tags->first()->owner_type)->toBeNull();
});

it('resolveExistingTagIds does not match owned tags by name', function () {
    $user = TestUser::create(['name' => 'Salem']);

    Tag::create([
        'name' => ['en' => 'vip'],
        'type' => null,
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    $model = TestModel::create(['name' => 'Post']);

    // hasTag uses resolveExistingTagIds internally — should not find the owned tag
    expect($model->hasTag('vip', null, 'en'))->toBeFalse();
});

it('attachTag by name does not attach an owned tag from another user', function () {
    $user = TestUser::create(['name' => 'Salem']);

    $owned = Tag::create([
        'name' => ['en' => 'vip'],
        'type' => null,
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    $model = TestModel::create(['name' => 'Post']);
    $model->attachTag('vip', null, 'en');

    // Must have created a new global tag, not reused the owned one
    $attachedTag = $model->tags->first();
    expect($attachedTag->id)->not->toBe($owned->id);
    expect($attachedTag->owner_type)->toBeNull();
});

it('withAllTags does not require owned tag when global and owned share a name', function () {
    $user = TestUser::create(['name' => 'Salem']);

    // Global vip tag
    $global = Tag::create(['name' => ['en' => 'vip'], 'type' => null]);

    // Owned vip tag (same name, different owner)
    Tag::create([
        'name' => ['en' => 'vip'],
        'type' => null,
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    $model = TestModel::create(['name' => 'Post']);
    $model->tags()->attach($global->id);

    // Should resolve only the global tag ID, so the model matches
    $results = TestModel::withAllTags(['vip'], null, 'en')->get();
    expect($results)->toHaveCount(1);
});

it('hasAllTags returns true when model has global tag and owned tag with same name exists', function () {
    $user = TestUser::create(['name' => 'Salem']);

    $global = Tag::create(['name' => ['en' => 'vip'], 'type' => null]);

    Tag::create([
        'name' => ['en' => 'vip'],
        'type' => null,
        'owner_type' => $user->getMorphClass(),
        'owner_id' => $user->getKey(),
    ]);

    $model = TestModel::create(['name' => 'Post']);
    $model->tags()->attach($global->id);

    expect($model->hasAllTags(['vip'], null, 'en'))->toBeTrue();
});

// ── Bug 7: withAllTags([]) must not return all records ────────────────────────

it('withAllTags with empty array returns no results', function () {
    TestModel::create(['name' => 'Post']);
    TestModel::create(['name' => 'Article']);

    $results = TestModel::withAllTags([])->get();

    expect($results)->toHaveCount(0);
});

it('withAllTags with empty collection returns no results', function () {
    TestModel::create(['name' => 'Post']);

    $results = TestModel::withAllTags(collect([]))->get();

    expect($results)->toHaveCount(0);
});
