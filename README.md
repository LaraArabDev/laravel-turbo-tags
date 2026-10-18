<p align="center">
    <img src="art/banner.svg" alt="TurboTags Banner" style="width: 100%; max-width: 800px;">
</p>

<h1 align="center">TurboTags</h1>

<p align="center">
    <strong>A smart, powerful way to manage tags and labels for any Laravel model</strong>
</p>

<p align="center">
    <a href="https://packagist.org/packages/laraarabdev/laravel-turbo-tags"><img src="https://img.shields.io/packagist/v/laraarabdev/laravel-turbo-tags?style=for-the-badge&logo=packagist&logoColor=white&color=f14621" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/laraarabdev/laravel-turbo-tags"><img src="https://img.shields.io/packagist/dt/laraarabdev/laravel-turbo-tags?style=for-the-badge&logo=packagist&logoColor=white&color=blue" alt="Total Downloads"></a>
    <a href="LICENSE"><img src="https://img.shields.io/packagist/l/laraarabdev/laravel-turbo-tags?style=for-the-badge&color=green" alt="License"></a>
    <a href="https://php.net"><img src="https://img.shields.io/packagist/php-v/laraarabdev/laravel-turbo-tags?style=for-the-badge&logo=php&logoColor=white&color=777BB4" alt="PHP"></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/laravel-11.x%20|%2012.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
</p>

<p align="center">
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/tests.yml?branch=main&label=tests&style=for-the-badge&logo=github&logoColor=white" alt="Tests"></a>
    <a href="https://codecov.io/gh/LaraArabDev/laravel-turbo-tags"><img src="https://img.shields.io/codecov/c/github/LaraArabDev/laravel-turbo-tags?style=for-the-badge&logo=codecov&logoColor=white" alt="Coverage"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/static-analysis.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/static-analysis.yml?branch=main&label=phpstan&style=for-the-badge&logo=github&logoColor=white" alt="Static Analysis"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/code-style.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/code-style.yml?branch=main&label=pint&style=for-the-badge&logo=github&logoColor=white" alt="Code Style"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/mutation-testing.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/mutation-testing.yml?branch=main&label=mutation&style=for-the-badge&logo=github&logoColor=white" alt="Mutation Testing"></a>
</p>

<p align="center">
    PHP 8.2+ &middot; Laravel 11 / 12 &middot; 95%+ Test Coverage &middot; PHPStan Level Max &middot; Zero External Dependencies
</p>

<p align="center">
    <a href="#-quick-start">Quick Start</a> &middot;
    <a href="#-why-turbotags">Why TurboTags</a> &middot;
    <a href="#-features">Features</a> &middot;
    <a href="#-usage">Usage</a> &middot;
    <a href="#-tag-reporting--analytics">Reporting</a> &middot;
    <a href="#%EF%B8%8F-configuration">Configuration</a> &middot;
    <a href="#-dependencies">Dependencies</a> &middot;
    <a href="#-testing">Testing</a> &middot;
    <a href="README.ar.md">العربية</a>
</p>

<p align="center">
    <b><a href="https://github.com/LaraArabDev">LaraArabDev</a></b> — We build, develop, empower, and contribute. An Arab open-source community crafting production-grade Laravel packages.
</p>

---

## What is TurboTags?

**TurboTags** is a smart, all-in-one tagging and labeling system for Laravel. It gives you a powerful, flexible way to organize and categorize **any Eloquent model** — posts, products, users, orders, or anything else — using tags (also known as labels, categories, or keywords).

Instead of building your own tagging logic from scratch or cobbling together multiple packages, TurboTags provides everything you need in a single package:

- **Tag any model** — Add the `HasTags` trait to any Eloquent model and start tagging instantly. One trait, unlimited models.
- **Multilingual out of the box** — Tag names are stored as JSON, supporting unlimited languages with an automatic fallback chain. No external translation package needed.
- **Organize with hierarchies** — Build tag trees like `Programming > PHP > Laravel`. Navigate with `ancestors()`, `descendants()`, and recursive eager loading.
- **Categorize with types** — Group tags into categories (e.g., "language", "framework", "skill") using simple strings or type-safe PHP `BackedEnum`.
- **Own and scope tags** — Tags can be global, user-owned, or team-scoped. Perfect for multi-tenant apps or user-generated tag systems.
- **Analyze tag usage** — Built-in reporting gives you the most popular tags, trending tags, and recently created tags.
- **Cache intelligently** — A built-in cache layer with automatic invalidation keeps your app fast without stale data.

Whether you're building a blog with categories, an e-commerce platform with product labels, a knowledge base with topics, or a social platform with hashtags — TurboTags handles it all with a clean, intuitive API.

---

## Quick Start

### Installation

```bash
composer require laraarabdev/laravel-turbo-tags
```

Publish the configuration file and database migrations, then run the migrations:

```bash
php artisan vendor:publish --tag="laravel-turbo-tags-config"
php artisan vendor:publish --tag="laravel-turbo-tags-migrations"
php artisan migrate
```

### Setup

Add the `HasTags` trait to any model you want to tag:

```php
use LaraArabDev\TurboTags\Concerns\HasTags;

class Post extends Model
{
    use HasTags;
}
```

### Start tagging

```php
// Attach tags — they are automatically created if they don't exist
$post->attachTags(['Laravel', 'PHP', 'Open Source']);

// Check tags
$post->hasTag('Laravel'); // true

// Query models by tags
Post::withAllTags(['Laravel', 'PHP'])->get();

// Multilingual tags
$tag = Tag::create([
    'name' => ['en' => 'Technology', 'ar' => 'تقنية'],
]);
```

That's it. No complex setup, no external dependencies, no boilerplate.

### Requirements

| Requirement | Version |
| --- | --- |
| PHP | 8.2 or higher |
| Laravel | 11.x or 12.x |
| External packages | **None** — everything is built in |

---

## Why TurboTags?

### Built-in translations — zero extra packages

Other tagging packages require you to install a separate translation package (like `spatie/laravel-translatable`) for multilingual support. TurboTags stores translations natively as JSON in the database with an intelligent fallback chain:

**Fallback order:** requested locale → config primary locale → app locale → config fallback locale → first available translation.

This means your users always see a meaningful tag name, even if the preferred translation doesn't exist yet.

```php
$tag = Tag::create([
    'name' => ['en' => 'Technology', 'ar' => 'تقنية', 'fr' => 'Technologie'],
]);

$tag->getTranslatedName('ar'); // "تقنية"
$tag->getTranslatedName();     // Uses your app locale automatically
```

For single-language apps, you don't need to configure anything — just pass a string and TurboTags wraps it in the app locale for you.

### Smart tag management for any model

TurboTags uses Laravel's polymorphic `morphToMany` relationships, which means **the same tagging system works across all your models**. A `Post`, a `Product`, and a `User` can all share the same tag pool — or you can separate them by type. You don't need a separate tags table for each model.

```php
// Same tags, different models
$post->attachTag('Featured');
$product->attachTag('Featured');

// Find everything tagged "Featured"
$tag->taggedModels(Post::class);    // All featured posts
$tag->taggedModels(Product::class); // All featured products
```

### Hierarchical tags out of the box

Build tag trees like `Programming → PHP → Laravel`. Load entire subtrees with a single eager-loaded query. Navigate up with `ancestors()`, down with `descendants()`. Circular references are automatically prevented at both the database and model level.

### Type-safe with PHP enums

Categorize tags using native PHP `BackedEnum` — no magic strings that can be misspelled:

```php
enum TagType: string {
    case Language = 'language';
    case Framework = 'framework';
}

$post->attachTag('Laravel', TagType::Framework);
$post->syncTagsWithType(['PHP', 'Go'], TagType::Language);
```

### Production-tested quality

- **95%+ code coverage** across 101 tests
- **PHPStan level max** — zero static analysis errors
- **Laravel Pint** — consistent code style
- **Soft deletes**, **cache auto-invalidation**, **circular reference protection**

---

## Features

| Feature | Description |
| --- | --- |
| **Polymorphic tagging** | Tag any Eloquent model using Laravel's `morphToMany`. One tagging system for your entire app. |
| **Built-in translations** | Multilingual tag names stored as JSON — no external translation packages needed. Smart fallback chain ensures users always see a name. |
| **Hierarchical tags** | Parent-child tree structure with recursive eager loading. Navigate with `ancestors()`, `descendants()`, `isRoot()`, `isLeaf()`. |
| **Tag types** | Categorize tags (e.g., `language`, `framework`, `category`) using plain strings or type-safe PHP `BackedEnum`. |
| **Tag ownership** | Scope tags globally, per user, or per team. Query with `global()`, `ownedBy($user)`, `availableTo($user)`. |
| **Query scopes** | Powerful filtering: `withAllTags`, `withAnyTags`, `withoutTags`, `withTagsOfType`, `withTagCount`. |
| **Tag reporting** | Analytics: `mostPopular()`, `recent()`, `recentMostPopular()` — find trending, popular, and new tags. |
| **Auto slug generation** | URL-friendly slugs generated automatically from the primary locale name, with uniqueness enforcement (`-2`, `-3` suffixes). |
| **Tag suggestions** | Search-as-you-type autocomplete with configurable minimum search length and result limit. |
| **Smart caching** | Configurable cache layer with automatic invalidation on create/update/delete. Deferred flushing for batch operations. |
| **Sync operations** | Replace tags globally or per type with `syncTags` and `syncTagsWithType`. Other tag types are preserved during type-specific sync. |
| **Metadata storage** | Attach arbitrary JSON data to any tag via the `metadata` column — colors, icons, descriptions, anything. |
| **Custom ordering** | Optional `order_column` for manual sort order. Query with `ordered()` scope. |
| **Circular reference protection** | Prevents invalid parent-child cycles automatically, even through soft-deleted tags. Throws `InvalidArgumentException` if detected. |
| **Soft deletes** | Tags support soft deleting. Child tags get `parent_id` set to `null` on parent deletion (`nullOnDelete`). |
| **Batch operations** | `findOrCreateMany` finds existing tags and creates missing ones in bulk, with only a single cache flush at the end. |

---

## Usage

### Attaching & detaching tags

```php
// By name — tags are automatically created if they don't exist
$post->attachTag('Laravel');
$post->attachTags(['Laravel', 'PHP', 'Testing']);

// By model instance or ID
$tag = Tag::findOrCreate('Laravel');
$post->attachTag($tag);
$post->attachTag($tag->id);

// With a type — categorize your tags
$post->attachTag('PHP', 'language');
$post->attachTag('Laravel', 'framework');

// With a type and locale (for multilingual apps)
$post->attachTag('تقنية', 'category', 'ar');

// Detach tags
$post->detachTag('Laravel');
$post->detachTags(['Laravel', 'PHP']);
$post->removeAllTags();
```

### Syncing tags

Sync replaces all existing tags with the ones you provide. It's the easiest way to update a model's tags from a form submission:

```php
// Replace ALL tags on the model
$post->syncTags(['Laravel', 'PHP']);

// Replace only tags of a specific type (other types are preserved)
// This is useful when you have separate tag inputs for different categories
$post->syncTagsWithType(['Python', 'Ruby'], 'language');
```

### Checking tags

```php
$post->hasTag('Laravel');                // true / false
$post->hasAllTags(['Laravel', 'PHP']);   // true only if ALL are present
$post->hasAnyTags(['Laravel', 'Ruby']); // true if ANY is present

// Check with type
$post->hasTag('PHP', 'language');        // true only if tagged as type "language"
```

### Query scopes

Use query scopes to find models based on their tags:

```php
// Models tagged with ALL of these tags (AND logic)
Post::withAllTags(['Laravel', 'PHP'])->get();

// Models tagged with ANY of these tags (OR logic)
Post::withAnyTags(['Laravel', 'Python'])->get();

// Models NOT tagged with these tags (exclusion)
Post::withoutTags(['Deprecated'])->get();

// Models with tags of a specific type
Post::withTagsOfType('language')->get();

// Eager load tags to avoid N+1 queries
Post::withTagsLoaded()->get();

// Load tag count without loading the actual tags
Post::withTagCount()->get();
```

---

### Hierarchical Tags

Build tag trees with parent-child relationships. This is useful for categories, taxonomies, nested navigation, skill trees, or any structure where tags have a natural hierarchy.

```php
// Create a tree: Programming → PHP → Laravel
$programming = Tag::create(['name' => ['en' => 'Programming', 'ar' => 'برمجة']]);
$php = Tag::create(['name' => ['en' => 'PHP'], 'parent_id' => $programming->id]);
$laravel = Tag::create(['name' => ['en' => 'Laravel'], 'parent_id' => $php->id]);

// Navigate the tree
$laravel->parent;               // → PHP tag
$programming->children;         // → Collection of [PHP]
$laravel->isLeaf();             // true (no children)
$programming->isRoot();         // true (no parent)

// Load entire subtree in one eager-loaded query
$programming->load('childrenRecursive');

// Get all ancestors from bottom to top
$laravel->ancestors();          // Collection: [PHP, Programming]

// Get all descendants (flattened)
$programming->descendants();    // Collection: [PHP, Laravel]

// Query only root tags (tags with no parent)
Tag::roots()->get();
```

**Circular references are prevented automatically.** Setting a tag's parent to itself or to one of its own descendants throws `InvalidArgumentException`. This protection works even through soft-deleted tags, with a depth limit of 100 to prevent stack overflow.

---

### Translations (Built-in)

TurboTags does **not** require any external translation package. Translations are stored directly in a JSON column with an automatic fallback chain, so your users always see a meaningful name.

```php
// Create a tag with multiple translations
$tag = Tag::create([
    'name' => ['en' => 'Technology', 'ar' => 'تقنية', 'fr' => 'Technologie'],
]);

// Get translated name
$tag->getTranslatedName('en');  // "Technology"
$tag->getTranslatedName('ar');  // "تقنية"
$tag->getTranslatedName();      // Uses app locale automatically

// Set or update a translation
$tag->setTranslatedName('Tecnologia', 'es');
$tag->save();

// Check and list translations
$tag->hasTranslation('ar');     // true
$tag->getTranslations();        // ['en' => 'Technology', 'ar' => 'تقنية', ...]
```

**Fallback chain:** requested locale → config `locale.primary` → `app()->getLocale()` → config `locale.fallback` → first available translation.

---

### Tag Ownership

Scope tags to specific users, teams, or any Eloquent model. This is essential for multi-tenant applications or systems where users create their own tags.

```php
// Create a user-owned tag
$tag = Tag::create([
    'name' => ['en' => 'Favorites'],
    'owner_type' => User::class,
    'owner_id' => $user->id,
]);

// Query scopes for ownership
Tag::global()->get();               // Tags with no owner (shared across the app)
Tag::ownedBy($user)->get();         // Tags belonging to this specific user
Tag::availableTo($user)->get();     // Global tags + this user's own tags
```

---

### Tag Types & Enums

Organize tags into categories. You can use plain strings or type-safe PHP `BackedEnum` (recommended to avoid typos):

```php
// Using strings
$post->attachTag('PHP', 'language');
Tag::ofType('language')->get();

// Using BackedEnum (recommended)
enum TagType: string
{
    case Category = 'category';
    case Label = 'label';
    case Language = 'language';
}

$tag = Tag::findOrCreate('PHP', TagType::Language, 'en');
$post->attachTag('Laravel', TagType::Category);
$post->syncTagsWithType(['PHP', 'Go'], TagType::Language);
Tag::ofType(TagType::Language)->get();
```

---

### Tag Suggestions

Build autocomplete and search-as-you-type functionality with the built-in suggestion system:

```php
// Search for tags containing "Lara"
$suggestions = Tag::suggestions('Lara');

// With type filtering, locale, and custom limit
$suggestions = Tag::suggestions('PH', 'language', 'en', 5);
```

The minimum search length is configurable (default: 2 characters) to prevent overly broad queries that return too many results.

---

### Active / Inactive Tags

Tags have an `is_active` flag (default: `true`) for visibility control. Inactive tags are automatically excluded from `findOrCreate`, `suggestions`, and query scopes (`withAllTags`, `withAnyTags`, `withoutTags`):

```php
// Deactivate a tag without deleting it
$tag->update(['is_active' => false]);

// Query only active or inactive tags
Tag::active()->get();
Tag::inactive()->get();

// Inactive tags are hidden from tag resolution and scopes
Post::withAllTags(['Laravel'])->get(); // Ignores inactive "Laravel" tag
Tag::suggestions('Lara');              // Skips inactive tags
```

This is separate from soft deletes — use `is_active` for toggling visibility, and soft deletes for actual deletion.

---

### Tag Metadata

Attach arbitrary JSON data to any tag via the `metadata` column. Use it to store colors, icons, descriptions, display settings, or any custom fields your application needs:

```php
// Create a tag with metadata
$tag = Tag::create([
    'name' => ['en' => 'Important'],
    'metadata' => [
        'color' => '#ff0000',
        'icon' => 'star',
        'description' => 'High priority items',
        'display_order' => 1,
    ],
]);

// Access metadata fields
$tag->metadata['color'];       // '#ff0000'
$tag->metadata['icon'];        // 'star'

// Update metadata
$tag->update([
    'metadata' => ['color' => '#00ff00', 'icon' => 'check', 'badge' => true],
]);

// Use with types and enums
$tag = Tag::findOrCreate('PHP', TagType::Language, 'en');
$tag->update([
    'metadata' => ['color' => '#4F5B93', 'logo' => 'php.svg'],
]);

// Use metadata in your views
@foreach($post->tags as $tag)
    <span style="background: {{ $tag->metadata['color'] ?? '#gray' }}">
        {{ $tag->getTranslatedName() }}
    </span>
@endforeach
```

Metadata is schemaless — there is no validation on what keys you store. It is cast to a PHP array automatically.

---

### Tag Reporting & Analytics

TurboTags includes built-in reporting methods to help you understand how tags are being used across your application:

```php
use LaraArabDev\TurboTags\Models\Tag;

// Get the most popular tags (ordered by total usage count across all models)
$topTags = Tag::mostPopular(10);            // Top 10 most used tags
$topLangs = Tag::mostPopular(5, 'language'); // Top 5 most used language tags

// Get the most recently created tags
$newTags = Tag::recent(10);                 // 10 newest tags
$newLangs = Tag::recent(5, 'language');     // 5 newest language tags

// Get trending tags — most popular within a recent time window
$trending = Tag::recentMostPopular(10, 30);          // Top 10 in the last 30 days
$trending = Tag::recentMostPopular(5, 7, 'language'); // Top 5 languages in the last 7 days
```

All reporting methods return tags with a `taggables_count` attribute showing how many models are tagged with each tag. Use this for tag clouds, trending sections, sidebar widgets, and admin dashboards.

---

### Caching

Enable the built-in cache layer for high-traffic applications. The cache is **automatically invalidated** whenever tags are created, updated, or deleted — no manual flushing needed.

```php
// config/laravel-turbo-tags.php
'cache' => [
    'enabled' => true,
    'ttl' => 3600,             // Cache lifetime in seconds (1 hour)
    'store' => null,            // null = your app's default cache driver
    'key_prefix' => 'turbo_tags',
],
```

Cached methods: `findOrCreate`, `allCached`, `allOfTypeCached`, `suggestions`.

```php
// Get all tags from cache (fast repeated access)
$tags = Tag::allCached();
$tags = Tag::allOfTypeCached('language');

// Batch operations use deferred flushing — only 1 cache flush instead of N
$tags = Tag::findOrCreateMany(['PHP', 'Laravel', 'Go']);

// Manual flush if needed
Tag::flushTagCache();
```

---

### findOrCreate — Efficient tag resolution

Efficiently find existing tags or create new ones in a single call:

```php
// Single tag — uses cache if enabled
$tag = Tag::findOrCreate('Laravel', 'framework', 'en');

// Batch — 1 query to find existing, creates only missing ones, 1 cache flush total
$tags = Tag::findOrCreateMany(['PHP', 'Laravel', 'Testing'], 'framework', 'en');
```

---

## Dependencies

### Production Dependencies

TurboTags has **zero external package dependencies**. It only requires Laravel's core framework components, which are already part of every Laravel application:

| Package | Version | What it provides |
| --- | --- | --- |
| `php` | ^8.2 | Minimum PHP version for modern type features (enums, named arguments, fibers) |
| `illuminate/contracts` | ^11.0 \| ^12.0 | Laravel's interface contracts. Provides the base interfaces that TurboTags implements, ensuring compatibility with Laravel's service container and dependency injection. |
| `illuminate/database` | ^11.0 \| ^12.0 | Laravel's Eloquent ORM and database layer. TurboTags uses this for models, migrations, relationships (morphToMany), query scopes, and soft deletes. This is the core of how tags are stored and queried. |
| `illuminate/support` | ^11.0 \| ^12.0 | Laravel's support utilities (Collections, Str helpers, ServiceProvider, etc.). Used for the service provider, cache management, slug generation, and collection operations. |

**These are not extra packages you need to install.** They ship with every Laravel application automatically. When you run `composer require laraarabdev/laravel-turbo-tags`, Composer resolves them from your existing Laravel installation. No additional downloads, no dependency conflicts, no bloat.

### Development Dependencies (not installed in your app)

These packages are only used for developing and testing TurboTags itself. They are **not** installed when you require TurboTags in your project:

| Package | Purpose |
| --- | --- |
| `pestphp/pest` | Testing framework — runs the 101 test suite |
| `pestphp/pest-plugin-laravel` | Laravel-specific Pest testing helpers |
| `orchestra/testbench` | Simulates a full Laravel app environment for package testing |
| `phpstan/phpstan` | Static analysis to catch type errors before runtime |
| `larastan/larastan` | PHPStan rules tailored for Laravel conventions |
| `laravel/pint` | Code style fixer to maintain consistent formatting |

---

## Configuration

After publishing the configuration file (`php artisan vendor:publish --tag="laravel-turbo-tags-config"`), you'll find it at `config/laravel-turbo-tags.php`:

```php
return [
    // Use a custom Tag model (extend the base Tag if you need custom behavior)
    'tag_model' => \LaraArabDev\TurboTags\Models\Tag::class,

    // Database table names — customize if they conflict with your existing tables
    'tables' => [
        'tags' => 'tags',
        'taggables' => 'taggables',
    ],

    // Locale settings for translations
    // Set to null to use your app's locale (config('app.locale'))
    'locale' => [
        'primary' => null,       // e.g., 'en' or 'ar'
        'fallback' => null,      // e.g., 'en'
    ],

    // Slug generation — auto-creates URL-friendly slugs from tag names
    'slugger' => [
        'source' => 'name',
        'generate_on_create' => true,   // Generate slug automatically on creation
        'generate_unique' => true,      // Append -2, -3, etc. to ensure uniqueness
    ],

    // Caching — enable in production for faster tag lookups
    'cache' => [
        'enabled' => false,      // Set to true in production
        'ttl' => 3600,           // Cache lifetime in seconds
        'store' => null,         // null = your app's default cache driver
        'key_prefix' => 'turbo_tags',
    ],

    // Performance tuning
    'performance' => [
        'chunk_size' => 1000,    // Chunk size for bulk database operations
    ],

    // Suggestion / autocomplete settings
    'suggestions' => [
        'limit' => 10,           // Maximum number of suggestions returned
        'min_length' => 2,       // Minimum characters before suggestions activate
    ],
];
```

---

## Database Schema

TurboTags creates two tables when you run the migration:

### `tags` table

| Column | Type | Description |
| --- | --- | --- |
| `id` | bigint (PK) | Primary key |
| `name` | JSON | Tag name — stores translations as `{"en": "Tech", "ar": "تقنية"}` |
| `slug` | string (unique) | Auto-generated URL-friendly identifier |
| `type` | string (nullable, indexed) | Tag category — e.g., "language", "framework" |
| `order_column` | integer (nullable) | Optional manual sort order |
| `is_active` | boolean (default: true) | Whether the tag is active (inactive tags are excluded from queries) |
| `metadata` | JSON (nullable) | Arbitrary JSON data for custom fields |
| `owner_type` | string (nullable) | Polymorphic owner model class |
| `owner_id` | bigint (nullable) | Polymorphic owner model ID |
| `parent_id` | bigint (nullable, indexed) | Self-referencing foreign key for hierarchy |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |
| `deleted_at` | timestamp (nullable) | Soft delete timestamp |

### `taggables` table (pivot)

| Column | Type | Description |
| --- | --- | --- |
| `id` | bigint (PK) | Primary key |
| `tag_id` | bigint (FK) | References `tags.id` — cascades on delete |
| `taggable_type` | string | The tagged model's class name |
| `taggable_id` | bigint | The tagged model's ID |
| `created_at` | timestamp | When the tag was attached |
| `updated_at` | timestamp | Last update |

A unique constraint on `(tag_id, taggable_id, taggable_type)` prevents duplicate tag attachments.

---

## Testing

The package has **101 tests** with **95%+ code coverage** and passes **PHPStan at max level**.

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test-coverage

# Static analysis (PHPStan max level)
composer analyse

# Check code style (Laravel Pint)
composer format-test

# Fix code style automatically
composer format
```

---

## API Reference

### `HasTags` Trait — Methods for your models

| Method | Description |
| --- | --- |
| `tags()` | Returns the `morphToMany` relationship to tags |
| `tagsOfType($type)` | Get tags filtered by type |
| `attachTag($tags, $type, $locale)` | Attach one or more tags |
| `attachTags($tags, $type, $locale)` | Alias for `attachTag` |
| `detachTag($tags, $type, $locale)` | Detach one or more tags |
| `detachTags($tags, $type, $locale)` | Alias for `detachTag` |
| `syncTags($tags, $type, $locale)` | Replace all tags (or per type) |
| `syncTagsWithType($tags, $type, $locale)` | Sync only tags of a specific type, preserving others |
| `removeAllTags()` | Remove all tags from the model |
| `hasTag($tag, $type, $locale)` | Check if model has a specific tag |
| `hasAllTags($tags, $type, $locale)` | Check if model has all given tags |
| `hasAnyTags($tags, $type, $locale)` | Check if model has any of the given tags |

### `HasTags` Trait — Query Scopes

| Scope | Description |
| --- | --- |
| `withAllTags($tags, $type, $locale)` | Models with ALL specified tags |
| `withAnyTags($tags, $type, $locale)` | Models with ANY of the specified tags |
| `withoutTags($tags, $type, $locale)` | Models without the specified tags |
| `withTagsOfType($type)` | Models that have at least one tag of the given type |
| `withTagsLoaded()` | Eager load the `tags` relationship |
| `withTagCount()` | Add a `tags_count` attribute to the query |

### `Tag` Model — Static Methods

| Method | Description |
| --- | --- |
| `Tag::findOrCreate($name, $type, $locale)` | Find an existing tag or create a new one |
| `Tag::findOrCreateMany($names, $type, $locale)` | Batch find or create multiple tags |
| `Tag::allCached()` | Get all tags from cache |
| `Tag::allOfTypeCached($type)` | Get cached tags filtered by type |
| `Tag::suggestions($search, $type, $locale, $limit)` | Search for tag suggestions (autocomplete) |
| `Tag::flushTagCache()` | Manually flush all cached tags |
| `Tag::mostPopular($limit, $type)` | Get tags ordered by usage count |
| `Tag::recent($limit, $type)` | Get most recently created tags |
| `Tag::recentMostPopular($limit, $days, $type)` | Get popular tags within a time window |

### `Tag` Model — Query Scopes

| Scope | Description |
| --- | --- |
| `Tag::active()` | Only active tags (`is_active = true`) |
| `Tag::inactive()` | Only inactive tags (`is_active = false`) |
| `Tag::roots()` | Only root tags (no parent) |
| `Tag::global()` | Only tags with no owner |
| `Tag::ownedBy($model)` | Tags owned by a specific model |
| `Tag::availableTo($model)` | Global tags + tags owned by the model |
| `Tag::ofType($type)` | Filter by tag type |
| `Tag::containing($search, $locale)` | Tags whose name contains the search string |
| `Tag::ordered($direction)` | Sort by `order_column` |
| `Tag::withSlug($slug)` | Find by slug |

### `Tag` Model — Instance Methods

| Method | Description |
| --- | --- |
| `$tag->parent` | Get the parent tag (BelongsTo relationship) |
| `$tag->children` | Get direct child tags (HasMany relationship) |
| `$tag->childrenRecursive` | Get the full subtree, eager-loadable |
| `$tag->ancestors()` | Get all ancestors from bottom to top |
| `$tag->descendants()` | Get all descendants, flattened |
| `$tag->isRoot()` | Returns `true` if the tag has no parent |
| `$tag->isLeaf()` | Returns `true` if the tag has no children |
| `$tag->getTranslatedName($locale)` | Get the tag name in a specific locale |
| `$tag->setTranslatedName($value, $locale)` | Set or update a translation |
| `$tag->getTranslations()` | Get all translations as an array |
| `$tag->hasTranslation($locale)` | Check if a specific translation exists |
| `$tag->taggedModels(Model::class)` | Get all models of a given class tagged with this tag |

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

We welcome contributions from the community! Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details. All contributions must meet our [acceptance criteria](.github/CONTRIBUTING.md#acceptance-criteria).

### Commit Convention

This project follows [Conventional Commits](https://www.conventionalcommits.org/). All commit messages and PR titles must follow this format:

```
type(scope): description
```

**Allowed types:** `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, `revert`, `hotfix`

**Branch naming:** `type/short-description` (e.g., `feat/add-caching`, `fix/slug-generation`)

## Security

Please review [our security policy](SECURITY.md) on how to report security vulnerabilities. **Do not** open a public issue.

## Policies

| Document | Description |
| --- | --- |
| [Code of Conduct](.github/CODE_OF_CONDUCT.md) | Community standards and expectations |
| [Contributing Guide](.github/CONTRIBUTING.md) | How to contribute, conventions, and acceptance criteria |
| [Security Policy](SECURITY.md) | How to report vulnerabilities responsibly |
| [Support](.github/SUPPORT.md) | How to get help |
| [Governance](.github/GOVERNANCE.md) | Decision-making process and roles |
| [Code Owners](.github/CODEOWNERS) | Required reviewers by area |

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

<p align="center">
    <sub>Built with &#10084; by <a href="https://github.com/LaraArabDev">LaraArabDev</a> — An Arab open-source community</sub>
</p>
