<?php

use LaraArabDev\TurboTags\Tests\Fixtures\TestModel;

beforeEach(function () {
    $this->model = TestModel::create(['name' => 'Test']);
});

it('can sync tags', function () {
    $this->model->attachTags(['Laravel', 'PHP'], null, 'en');
    $this->model->syncTags(['PHP', 'Testing'], null, 'en');

    $this->model->load('tags');
    $tagNames = $this->model->tags->map(fn ($t) => $t->getTranslatedName('en'))->sort()->values()->toArray();

    expect($tagNames)->toBe(['PHP', 'Testing']);
});

it('can sync tags with type', function () {
    $this->model->attachTag('PHP', 'language', 'en');
    $this->model->attachTag('Laravel', 'framework', 'en');

    $this->model->syncTagsWithType(['Python', 'Ruby'], 'language', 'en');

    $this->model->load('tags');
    $languages = $this->model->tagsOfType('language')->pluck('slug')->sort()->values()->toArray();

    expect($languages)->toContain('python')
        ->and($languages)->toContain('ruby')
        ->and($languages)->not->toContain('php');

    // Framework tags should be untouched
    expect($this->model->tagsOfType('framework')->count())->toBe(1);
});

it('can sync with empty array to remove all tags', function () {
    $this->model->attachTags(['Laravel', 'PHP'], null, 'en');
    $this->model->syncTags([]);

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(0);
});

it('can sync with type preserving other types', function () {
    $this->model->attachTag('Red', 'color', 'en');
    $this->model->attachTag('Large', 'size', 'en');

    $this->model->syncTagsWithType(['Blue', 'Green'], 'color', 'en');

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(3); // Blue, Green (color) + Large (size)
    expect($this->model->tagsOfType('color')->count())->toBe(2);
    expect($this->model->tagsOfType('size')->count())->toBe(1);
});

it('handles sync with tag models', function () {
    $this->model->attachTags(['Laravel', 'PHP'], null, 'en');

    $tags = $this->model->tags;
    $this->model->syncTags($tags);

    $this->model->load('tags');
    expect($this->model->tags)->toHaveCount(2);
});
