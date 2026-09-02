<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\Tests\Fixtures\TestModel;

beforeEach(function () {
    config()->set('laravel-turbo-tags.locale.primary', 'en');
});

// --- mostPopular ---

it('returns tags ordered by usage count', function () {
    $tagA = Tag::findOrCreate('PHP');
    $tagB = Tag::findOrCreate('Laravel');
    $tagC = Tag::findOrCreate('Vue');

    $model1 = TestModel::create(['name' => 'Post 1']);
    $model2 = TestModel::create(['name' => 'Post 2']);
    $model3 = TestModel::create(['name' => 'Post 3']);

    $model1->attachTags([$tagA, $tagB, $tagC]);
    $model2->attachTags([$tagA, $tagB]);
    $model3->attachTag($tagA);

    $popular = Tag::mostPopular(10);

    expect($popular->first()->slug)->toBe('php')
        ->and($popular->first()->taggables_count)->toBe(3)
        ->and($popular[1]->slug)->toBe('laravel')
        ->and($popular[1]->taggables_count)->toBe(2)
        ->and($popular[2]->slug)->toBe('vue')
        ->and($popular[2]->taggables_count)->toBe(1);
});

it('filters most popular by type', function () {
    $lang = Tag::findOrCreate('PHP', 'language');
    $framework = Tag::findOrCreate('Laravel', 'framework');

    $model1 = TestModel::create(['name' => 'Post 1']);
    $model2 = TestModel::create(['name' => 'Post 2']);

    $model1->attachTags([$lang, $framework]);
    $model2->attachTag($framework);

    $popular = Tag::mostPopular(10, 'framework');

    expect($popular)->toHaveCount(1)
        ->and($popular->first()->slug)->toBe('laravel');
});

it('respects the limit parameter', function () {
    foreach (['PHP', 'Laravel', 'Vue', 'React', 'Svelte'] as $name) {
        Tag::findOrCreate($name);
    }

    $model = TestModel::create(['name' => 'Post']);
    $model->attachTags(['PHP', 'Laravel', 'Vue', 'React', 'Svelte']);

    $popular = Tag::mostPopular(3);

    expect($popular)->toHaveCount(3);
});

it('includes tags with zero usage in mostPopular', function () {
    Tag::findOrCreate('Unused');

    $popular = Tag::mostPopular(10);

    expect($popular)->toHaveCount(1)
        ->and($popular->first()->taggables_count)->toBe(0);
});

// --- recent ---

it('returns tags ordered by creation date descending', function () {
    Carbon::setTestNow('2025-01-01');
    Tag::findOrCreate('Old Tag');

    Carbon::setTestNow('2025-06-01');
    Tag::findOrCreate('Mid Tag');

    Carbon::setTestNow('2025-12-01');
    Tag::findOrCreate('New Tag');

    Carbon::setTestNow();

    $recent = Tag::recent(10);

    expect($recent->first()->slug)->toBe('new-tag')
        ->and($recent->last()->slug)->toBe('old-tag');
});

it('filters recent tags by type', function () {
    Tag::findOrCreate('PHP', 'language');
    Tag::findOrCreate('Laravel', 'framework');

    $recent = Tag::recent(10, 'language');

    expect($recent)->toHaveCount(1)
        ->and($recent->first()->slug)->toBe('php');
});

it('limits recent tags result count', function () {
    foreach (['A', 'B', 'C', 'D', 'E'] as $name) {
        Tag::findOrCreate($name);
    }

    $recent = Tag::recent(2);

    expect($recent)->toHaveCount(2);
});

// --- recentMostPopular ---

it('returns popular tags based on recent usage only', function () {
    $oldTag = Tag::findOrCreate('Old Popular');
    $newTag = Tag::findOrCreate('New Popular');

    $model1 = TestModel::create(['name' => 'Post 1']);
    $model2 = TestModel::create(['name' => 'Post 2']);
    $model3 = TestModel::create(['name' => 'Post 3']);

    // Old tagging activity (60 days ago)
    Carbon::setTestNow(Carbon::now()->subDays(60));
    $model1->attachTag($oldTag);
    $model2->attachTag($oldTag);

    // Recent tagging activity (5 days ago)
    Carbon::setTestNow(Carbon::now()->addDays(55));
    $model1->attachTag($newTag);
    $model2->attachTag($newTag);
    $model3->attachTag($newTag);

    Carbon::setTestNow();

    $popular = Tag::recentMostPopular(10, 30);

    expect($popular)->toHaveCount(1)
        ->and($popular->first()->slug)->toBe('new-popular')
        ->and($popular->first()->taggables_count)->toBe(3);
});

it('filters recentMostPopular by type', function () {
    $lang = Tag::findOrCreate('PHP', 'language');
    $framework = Tag::findOrCreate('Laravel', 'framework');

    $model = TestModel::create(['name' => 'Post']);
    $model->attachTags([$lang, $framework]);

    $popular = Tag::recentMostPopular(10, 30, 'framework');

    expect($popular)->toHaveCount(1)
        ->and($popular->first()->slug)->toBe('laravel');
});

it('excludes tags with no recent usage from recentMostPopular', function () {
    $tag = Tag::findOrCreate('Dormant');

    // Tag was used 60 days ago only
    Carbon::setTestNow(Carbon::now()->subDays(60));
    $model = TestModel::create(['name' => 'Post']);
    $model->attachTag($tag);
    Carbon::setTestNow();

    $popular = Tag::recentMostPopular(10, 30);

    expect($popular)->toHaveCount(0);
});
