<?php

use LaraArabDev\TurboTags\Tests\Fixtures\TagType;
use LaraArabDev\TurboTags\Tests\Fixtures\TestModel;

beforeEach(function () {
    $this->model1 = TestModel::create(['name' => 'Model 1']);
    $this->model2 = TestModel::create(['name' => 'Model 2']);
    $this->model3 = TestModel::create(['name' => 'Model 3']);

    $this->model1->attachTags(['Laravel', 'PHP'], null, 'en');
    $this->model2->attachTags(['Laravel', 'Python'], null, 'en');
    $this->model3->attachTag('Ruby', null, 'en');
});

it('can scope with all tags', function () {
    $results = TestModel::withAllTags(['Laravel', 'PHP'], null, 'en')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->model1->id);
});

it('can scope with any tags', function () {
    $results = TestModel::withAnyTags(['PHP', 'Python'], null, 'en')->get();

    expect($results)->toHaveCount(2);
});

it('can scope without tags', function () {
    $results = TestModel::withoutTags(['Laravel'], null, 'en')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($this->model3->id);
});

it('can scope with tags of type', function () {
    $model4 = TestModel::create(['name' => 'Model 4']);
    $model4->attachTag('Backend', 'category', 'en');

    $results = TestModel::withTagsOfType('category')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($model4->id);
});

it('can eager load tags', function () {
    $models = TestModel::withTagsLoaded()->get();

    expect($models->first()->relationLoaded('tags'))->toBeTrue();
});

it('can load tag count', function () {
    $models = TestModel::withTagCount()->get();

    $model1 = $models->firstWhere('id', $this->model1->id);

    expect($model1->tags_count)->toBe(2);
});

it('withAllTags returns empty for non-matching', function () {
    $results = TestModel::withAllTags(['Laravel', 'Ruby'], null, 'en')->get();

    expect($results)->toHaveCount(0);
});

it('withAnyTags returns empty for non-existing tags', function () {
    $results = TestModel::withAnyTags(['Rust', 'Go'], null, 'en')->get();

    expect($results)->toHaveCount(0);
});

it('can scope with tags of enum type', function () {
    $model4 = TestModel::create(['name' => 'Model 4']);
    $model4->attachTag('Backend', TagType::Category, 'en');
    $model4->attachTag('Important', TagType::Label, 'en');

    $results = TestModel::withTagsOfType(TagType::Category)->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($model4->id);
});

it('can scope with all tags using enum type', function () {
    $model4 = TestModel::create(['name' => 'Model 4']);
    $model4->attachTags(['Backend', 'Frontend'], TagType::Category, 'en');

    $results = TestModel::withAllTags(['Backend', 'Frontend'], TagType::Category, 'en')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($model4->id);
});
