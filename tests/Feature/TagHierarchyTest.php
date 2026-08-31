<?php

use LaraArabDev\TurboTags\Models\Tag;

it('creates root tags with null parent_id', function () {
    $tag = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);

    expect($tag->parent_id)->toBeNull();
});

it('can create a tag with a parent', function () {
    $parent = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $child = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $parent->id]);

    expect($child->parent_id)->toBe($parent->id);
});

it('returns the parent model via parent()', function () {
    $parent = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $child = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $parent->id]);

    expect($child->parent)->toBeInstanceOf(Tag::class)
        ->and($child->parent->id)->toBe($parent->id);
});

it('returns direct children via children()', function () {
    $parent = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $parent->id]);
    Tag::create(['name' => ['en' => 'Python'], 'slug' => 'python', 'parent_id' => $parent->id]);

    expect($parent->children)->toHaveCount(2);
});

it('loads full subtree via childrenRecursive', function () {
    $root = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $php = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $root->id]);
    Tag::create(['name' => ['en' => 'Laravel'], 'slug' => 'laravel', 'parent_id' => $php->id]);

    $root->load('childrenRecursive');

    expect($root->childrenRecursive)->toHaveCount(1)
        ->and($root->childrenRecursive->first()->childrenRecursive)->toHaveCount(1)
        ->and($root->childrenRecursive->first()->childrenRecursive->first()->slug)->toBe('laravel');
});

it('scopes to root tags only via scopeRoots()', function () {
    $root = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $root->id]);
    Tag::create(['name' => ['en' => 'Design'], 'slug' => 'design']);

    expect(Tag::roots()->count())->toBe(2);
});

it('returns correct boolean for isRoot()', function () {
    $root = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $child = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $root->id]);

    expect($root->isRoot())->toBeTrue()
        ->and($child->isRoot())->toBeFalse();
});

it('returns correct boolean for isLeaf()', function () {
    $root = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $child = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $root->id]);

    expect($root->isLeaf())->toBeFalse()
        ->and($child->isLeaf())->toBeTrue();
});

it('returns the full ancestor chain via ancestors()', function () {
    $root = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $php = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $root->id]);
    $laravel = Tag::create(['name' => ['en' => 'Laravel'], 'slug' => 'laravel', 'parent_id' => $php->id]);

    $ancestors = $laravel->ancestors();

    expect($ancestors)->toHaveCount(2)
        ->and($ancestors->first()->id)->toBe($php->id)
        ->and($ancestors->last()->id)->toBe($root->id);
});

it('returns all descendants flattened via descendants()', function () {
    $root = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $php = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $root->id]);
    $laravel = Tag::create(['name' => ['en' => 'Laravel'], 'slug' => 'laravel', 'parent_id' => $php->id]);
    $python = Tag::create(['name' => ['en' => 'Python'], 'slug' => 'python', 'parent_id' => $root->id]);

    $descendants = $root->descendants();

    expect($descendants)->toHaveCount(3)
        ->and($descendants->pluck('id')->sort()->values()->all())
        ->toBe([$php->id, $laravel->id, $python->id]);
});

it('throws InvalidArgumentException on circular reference', function () {
    $a = Tag::create(['name' => ['en' => 'A'], 'slug' => 'a']);
    $b = Tag::create(['name' => ['en' => 'B'], 'slug' => 'b', 'parent_id' => $a->id]);

    $a->parent_id = $b->id;
    $a->save();
})->throws(InvalidArgumentException::class, 'Circular parent reference detected.');

it('throws InvalidArgumentException when setting self as parent', function () {
    $a = Tag::create(['name' => ['en' => 'A'], 'slug' => 'a']);

    $a->parent_id = $a->id;
    $a->save();
})->throws(InvalidArgumentException::class, 'Circular parent reference detected.');

it('detects circular reference through soft-deleted tag', function () {
    $a = Tag::create(['name' => ['en' => 'A'], 'slug' => 'a']);
    $b = Tag::create(['name' => ['en' => 'B'], 'slug' => 'b', 'parent_id' => $a->id]);

    $a->delete(); // soft-delete

    $trashed = Tag::withTrashed()->find($a->id);
    $trashed->parent_id = $b->id;
    $trashed->save();
})->throws(InvalidArgumentException::class, 'Circular parent reference detected.');

it('nullifies children parent_id when parent is deleted', function () {
    $parent = Tag::create(['name' => ['en' => 'Programming'], 'slug' => 'programming']);
    $child = Tag::create(['name' => ['en' => 'PHP'], 'slug' => 'php', 'parent_id' => $parent->id]);

    $parent->forceDelete();

    $child->refresh();
    expect($child->parent_id)->toBeNull();
});
