<?php

use LaraArabDev\TurboTags\Models\Tag;
use LaraArabDev\TurboTags\Tests\Fixtures\TagType;
use LaraArabDev\TurboTags\Tests\Fixtures\TestModel;

beforeEach(function () {
    $this->model = TestModel::create(['name' => 'Test']);
});

it('can attach a tag by string', function () {
    $this->model->attachTag('Laravel', null, 'en');

    expect($this->model->tags)->toHaveCount(1)
        ->and($this->model->tags->first()->getTranslatedName('en'))->toBe('Laravel');
});

it('can attach a tag by model', function () {
    $tag = Tag::findOrCreate('Laravel', null, 'en');
    $this->model->attachTag($tag);

    expect($this->model->tags)->toHaveCount(1);
});

it('can attach a tag by id', function () {
    $tag = Tag::findOrCreate('Laravel', null, 'en');
    $this->model->attachTag($tag->id);

    expect($this->model->tags)->toHaveCount(1);
});

it('can attach multiple tags', function () {
    $this->model->attachTags(['Laravel', 'PHP', 'Testing'], null, 'en');

    expect($this->model->tags)->toHaveCount(3);
});

it('does not duplicate tags on attach', function () {
    $this->model->attachTag('Laravel', null, 'en');
    $this->model->attachTag('Laravel', null, 'en');

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(1);
});

it('can detach a tag', function () {
    $this->model->attachTags(['Laravel', 'PHP'], null, 'en');
    $this->model->detachTag('Laravel', null, 'en');

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(1)
        ->and($this->model->tags->first()->getTranslatedName('en'))->toBe('PHP');
});

it('can detach multiple tags', function () {
    $this->model->attachTags(['Laravel', 'PHP', 'Testing'], null, 'en');
    $this->model->detachTags(['Laravel', 'PHP'], null, 'en');

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(1);
});

it('can remove all tags', function () {
    $this->model->attachTags(['Laravel', 'PHP', 'Testing'], null, 'en');
    $this->model->removeAllTags();

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(0);
});

it('can check if model has a tag', function () {
    $this->model->attachTag('Laravel', null, 'en');

    expect($this->model->hasTag('Laravel', null, 'en'))->toBeTrue()
        ->and($this->model->hasTag('PHP', null, 'en'))->toBeFalse();
});

it('can check if model has all tags', function () {
    $this->model->attachTags(['Laravel', 'PHP'], null, 'en');

    expect($this->model->hasAllTags(['Laravel', 'PHP'], null, 'en'))->toBeTrue()
        ->and($this->model->hasAllTags(['Laravel', 'PHP', 'Testing'], null, 'en'))->toBeFalse();
});

it('can check if model has any tags', function () {
    $this->model->attachTag('Laravel', null, 'en');

    expect($this->model->hasAnyTags(['Laravel', 'PHP'], null, 'en'))->toBeTrue()
        ->and($this->model->hasAnyTags(['Python', 'Ruby'], null, 'en'))->toBeFalse();
});

it('can get tags of type', function () {
    $this->model->attachTag('PHP', 'language', 'en');
    $this->model->attachTag('Laravel', 'framework', 'en');

    expect($this->model->tagsOfType('language')->count())->toBe(1)
        ->and($this->model->tagsOfType('framework')->count())->toBe(1);
});

it('can attach a tag with enum type', function () {
    $this->model->attachTag('PHP', TagType::Category, 'en');

    expect($this->model->tags)->toHaveCount(1)
        ->and($this->model->tags->first()->type)->toBe('category');
});

it('can detach a tag with enum type', function () {
    $this->model->attachTags(['PHP', 'Laravel'], TagType::Category, 'en');
    $this->model->detachTag('PHP', TagType::Category, 'en');

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(1);
});

it('can check has tag with enum type', function () {
    $this->model->attachTag('PHP', TagType::Category, 'en');

    expect($this->model->hasTag('PHP', TagType::Category, 'en'))->toBeTrue()
        ->and($this->model->hasTag('PHP', TagType::Label, 'en'))->toBeFalse();
});

it('can get tags of enum type', function () {
    $this->model->attachTag('PHP', TagType::Category, 'en');
    $this->model->attachTag('Important', TagType::Label, 'en');

    expect($this->model->tagsOfType(TagType::Category)->count())->toBe(1)
        ->and($this->model->tagsOfType(TagType::Label)->count())->toBe(1);
});

it('can sync tags with enum type', function () {
    $this->model->attachTag('PHP', TagType::Category, 'en');
    $this->model->attachTag('Important', TagType::Label, 'en');

    $this->model->syncTagsWithType(['Laravel', 'Pest'], TagType::Category, 'en');

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(3)
        ->and($this->model->tagsOfType(TagType::Category)->count())->toBe(2)
        ->and($this->model->tagsOfType(TagType::Label)->count())->toBe(1);
});
