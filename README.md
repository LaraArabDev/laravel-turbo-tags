# Laravel TurboTags

[![Tests](https://github.com/LaraArabDev/LaravelTurboTags/actions/workflows/tests.yml/badge.svg)](https://github.com/LaraArabDev/LaravelTurboTags/actions/workflows/tests.yml)
[![Static Analysis](https://github.com/LaraArabDev/LaravelTurboTags/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/LaraArabDev/LaravelTurboTags/actions/workflows/static-analysis.yml)
[![Code Style](https://github.com/LaraArabDev/LaravelTurboTags/actions/workflows/code-style.yml/badge.svg)](https://github.com/LaraArabDev/LaravelTurboTags/actions/workflows/code-style.yml)

**Enterprise-grade, high-performance tagging & taxonomy framework for Laravel.**

TurboTags is a lightweight yet feature-rich tagging and taxonomy framework for Laravel. Built as a modern replacement for basic tagging solutions, TurboTags provides multi-locale translations, hierarchical parent-child tags, scoped visibility (Global, Team, and Personal/Jira-style tags), multiple tag types per model, polymorphic relationships with custom pivot payloads, and optimized caching engines.

## Features

- **Polymorphic Tagging** — Attach tags to any Eloquent model via the `HasTurboTags` trait
- **Multi-Locale Translations** — Translatable tag names, descriptions, and metadata via Spatie Translatable
- **Hierarchical Taxonomies** — Unlimited parent-child nested tag structures with tree scopes
- **Scoped Visibility** — Global, Team, and Personal (Jira-style) tag ownership
- **Multiple Tag Types** — Categorize tags per model (e.g., `specification` vs `marketing` tags)
- **Custom Pivot Payload** — `attached_by_id`, `tag_order`, and `metadata` on the pivot table
- **Localized Slugs** — Auto-generated per-locale slugs with history tracking and auto-redirects
- **Ordering & Sorting** — Pivot-level and global tag ordering with drag-and-drop support
- **Performance Caching** — Redis/Memcached cache layer with auto-invalidation
- **Denormalized Counters** — Built-in `usage_count` to avoid expensive COUNT queries
- **Batch Operations** — Optimized bulk attach/detach/sync

## Installation

You can install the package via Composer:

```bash
composer require laraarabdev/laravel-turbo-tags
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="turbo-tags-migrations"
php artisan migrate
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="turbo-tags-config"
```

## Quick Start

### Add the trait to your model

```php
use LaraArabDev\TurboTags\Concerns\HasTurboTags;

class Post extends Model
{
    use HasTurboTags;
}
```

### Create and attach tags

```php
use LaraArabDev\TurboTags\Models\TurboTag;

// Create a translatable tag
$tag = TurboTag::create([
    'name' => ['en' => 'Laravel', 'ar' => 'لارافيل'],
    'description' => ['en' => 'PHP Framework', 'ar' => 'إطار عمل PHP'],
    'type' => 'technology',
]);

// Attach tags to a model
$post->attachTags(['Laravel', 'PHP'], 'technology');

// Query by tag type
$post->tagsOfType('technology');

// Query models by tags
Post::withAnyTags(['Laravel', 'PHP'])->get();
Post::withAllTags(['Laravel', 'Tutorial'])->get();
```

### Scoped tags

```php
TurboTag::global()->get();
TurboTag::forTeam($team)->get();
TurboTag::personal($user)->get();
TurboTag::availableFor($user, $team)->get();
```

### Hierarchical tags

```php
$tag->children;
$tag->parent;
$tag->ancestors();
$tag->descendants();
$tag->breadcrumbString(' > '); // "Technology > PHP > Laravel"
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

## Code Style

```bash
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/PULL_REQUEST_TEMPLATE.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
