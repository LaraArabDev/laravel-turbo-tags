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
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/laravel-11.x%20|%2012.x%20|%2013.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
</p>

<p align="center">
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/tests.yml?branch=main&label=tests&style=for-the-badge&logo=github&logoColor=white" alt="Tests"></a>
    <a href="https://codecov.io/gh/LaraArabDev/laravel-turbo-tags"><img src="https://img.shields.io/codecov/c/github/LaraArabDev/laravel-turbo-tags?style=for-the-badge&logo=codecov&logoColor=white" alt="Coverage"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/static-analysis.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/static-analysis.yml?branch=main&label=phpstan&style=for-the-badge&logo=github&logoColor=white" alt="Static Analysis"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/code-style.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/code-style.yml?branch=main&label=pint&style=for-the-badge&logo=github&logoColor=white" alt="Code Style"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/mutation-testing.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/mutation-testing.yml?branch=main&label=mutation&style=for-the-badge&logo=github&logoColor=white" alt="Mutation Testing"></a>
</p>

<p align="center">
    Tag any Eloquent model with multilingual names, hierarchical trees, ownership scopes, and built-in analytics — zero external dependencies.<br>
    PHP 8.2 – 8.4 · Laravel 11 / 12 / 13
</p>

<p align="center">
    <b><a href="https://github.com/LaraArabDev">LaraArabDev</a></b> — We build, develop, empower, and contribute. An Arab open-source community crafting production-grade Laravel packages.<br>
    <b><a href="https://github.com/LaraArabDev">LaraArabDev</a></b> — نبني، نطوّر، نُمكّن، ونُساهم. مجتمع عربي مفتوح المصدر يصنع حزم Laravel احترافية وجاهزة للإنتاج.
</p>

<p align="center">
    <a href="#-quick-start">Quick Start</a> ·
    <a href="#-features">Features</a> ·
    <a href="#-usage">Usage</a> ·
    <a href="#-configuration">Configuration</a> ·
    <a href="#-faq">FAQ</a> ·
    <a href="#-العربية">العربية</a>
</p>

---

## What is TurboTags?

Every Laravel app eventually needs tags — whether it's a blog with categories, an e-commerce platform with product labels, a knowledge base with topics, or a social platform with hashtags.

**TurboTags** gives your Laravel app a complete tagging system with **zero external dependencies**. Install the package, add one trait to your models, and you're done. Every model can be tagged instantly with multilingual names, hierarchical trees, type categorization, and ownership scopes.

But simple tagging is just the beginning. TurboTags also provides:

- **Built-in translations** — Tag names stored as JSON with an intelligent fallback chain. No external translation package needed.
- **Hierarchical tags** — Build tag trees like `Programming > PHP > Laravel`. Navigate with `ancestors()`, `descendants()`, and recursive eager loading.
- **Tag types** — Categorize tags (e.g., "language", "framework", "skill") using plain strings or type-safe PHP `BackedEnum`.
- **Tag ownership** — Tags can be global, user-owned, or team-scoped. Perfect for multi-tenant apps.
- **Analytics & reporting** — Built-in methods for most popular, trending, and recently created tags.
- **Smart caching** — Configurable cache layer with automatic invalidation. No stale data.

**You decide what to use.** Want just simple tags? Add the trait. Need multilingual tags with hierarchies? It's built in. Every feature is independent — enable exactly the combination that fits your application.

---

## 🚀 Quick Start

Three commands. That's it.

```bash
composer require laraarabdev/laravel-turbo-tags
php artisan vendor:publish --tag="laravel-turbo-tags-config"
php artisan vendor:publish --tag="laravel-turbo-tags-migrations"
php artisan migrate
```

Now add the trait to any model you want to tag:

```php
use LaraArabDev\TurboTags\Concerns\HasTags;

class Post extends Model
{
    use HasTags;
}
```

Every model can now be tagged. No configuration files to edit, no arrays to maintain, no boilerplate.

```php
// Attach tags — automatically created if they don't exist
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

| Requirement | Version |
| --- | --- |
| PHP | 8.2, 8.3, or 8.4 |
| Laravel | 11, 12, or 13 |
| External packages | **None** — everything is built in |

---

## 📦 Features

| Feature | Description |
| --- | --- |
| **Polymorphic tagging** | Tag any Eloquent model using Laravel's `morphToMany`. One tagging system for your entire app. |
| **Built-in translations** | Multilingual tag names stored as JSON — no external translation packages needed. Smart fallback chain ensures users always see a name. |
| **Hierarchical tags** | Parent-child tree structure with recursive eager loading. Navigate with `ancestors()`, `descendants()`, `isRoot()`, `isLeaf()`. |
| **Tag types** | Categorize tags (e.g., `language`, `framework`, `category`) using plain strings or type-safe PHP `BackedEnum`. |
| **Tag ownership** | Scope tags globally, per user, or per team. Query with `global()`, `ownedBy($user)`, `availableTo($user)`. |
| **Query scopes** | Powerful filtering: `withAllTags`, `withAnyTags`, `withoutTags`, `withTagsOfType`, `withTagCount`. |
| **Tag reporting** | Analytics: `mostPopular()`, `recent()`, `recentMostPopular()` — find trending, popular, and new tags. |
| **Auto slug generation** | URL-friendly slugs generated automatically from the primary locale name, with uniqueness enforcement. |
| **Tag suggestions** | Search-as-you-type autocomplete with configurable minimum search length and result limit. |
| **Smart caching** | Configurable cache layer with automatic invalidation on create/update/delete. Deferred flushing for batch operations. |
| **Sync operations** | Replace tags globally or per type with `syncTags` and `syncTagsWithType`. Other tag types are preserved during type-specific sync. |
| **Metadata storage** | Attach arbitrary JSON data to any tag via the `metadata` column — colors, icons, descriptions, anything. |
| **Custom ordering** | Optional `order_column` for manual sort order. Query with `ordered()` scope. |
| **Circular reference protection** | Prevents invalid parent-child cycles automatically, even through soft-deleted tags. |
| **Soft deletes** | Tags support soft deleting. Child tags get `parent_id` set to `null` on parent deletion. |
| **Batch operations** | `findOrCreateMany` finds existing tags and creates missing ones in bulk, with only a single cache flush at the end. |

---

## 📖 Usage

### Attaching & Detaching Tags

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

### Syncing Tags

Sync replaces all existing tags with the ones you provide. It's the easiest way to update a model's tags from a form submission:

```php
// Replace ALL tags on the model
$post->syncTags(['Laravel', 'PHP']);

// Replace only tags of a specific type (other types are preserved)
$post->syncTagsWithType(['Python', 'Ruby'], 'language');
```

### Checking Tags

```php
$post->hasTag('Laravel');                // true / false
$post->hasAllTags(['Laravel', 'PHP']);   // true only if ALL are present
$post->hasAnyTags(['Laravel', 'Ruby']); // true if ANY is present

// Check with type
$post->hasTag('PHP', 'language');        // true only if tagged as type "language"
```

### Query Scopes

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

Build tag trees with parent-child relationships. Useful for categories, taxonomies, nested navigation, skill trees, or any structure where tags have a natural hierarchy.

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

For single-language apps, you don't need to configure anything — just pass a string and TurboTags wraps it in the app locale for you.

---

### Tag Ownership

Scope tags to specific users, teams, or any Eloquent model. Essential for multi-tenant applications or systems where users create their own tags.

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

The minimum search length is configurable (default: 2 characters) to prevent overly broad queries.

---

### Active / Inactive Tags

Tags have an `is_active` flag (default: `true`) for visibility control. Inactive tags are automatically excluded from `findOrCreate`, `suggestions`, and query scopes:

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

Attach arbitrary JSON data to any tag via the `metadata` column:

```php
$tag = Tag::create([
    'name' => ['en' => 'Important'],
    'metadata' => [
        'color' => '#ff0000',
        'icon' => 'star',
        'description' => 'High priority items',
    ],
]);

$tag->metadata['color']; // '#ff0000'

// Use metadata in your views
@foreach($post->tags as $tag)
    <span style="background: {{ $tag->metadata['color'] ?? '#gray' }}">
        {{ $tag->getTranslatedName() }}
    </span>
@endforeach
```

---

### Tag Reporting & Analytics

Built-in reporting methods to understand how tags are being used:

```php
use LaraArabDev\TurboTags\Models\Tag;

// Most popular tags (ordered by total usage count across all models)
$topTags = Tag::mostPopular(10);            // Top 10 most used tags
$topLangs = Tag::mostPopular(5, 'language'); // Top 5 most used language tags

// Most recently created tags
$newTags = Tag::recent(10);                 // 10 newest tags

// Trending tags — most popular within a recent time window
$trending = Tag::recentMostPopular(10, 30);          // Top 10 in the last 30 days
$trending = Tag::recentMostPopular(5, 7, 'language'); // Top 5 languages in the last 7 days
```

All reporting methods return tags with a `taggables_count` attribute. Use this for tag clouds, trending sections, sidebar widgets, and admin dashboards.

---

### Caching

Enable the built-in cache layer for high-traffic applications. The cache is **automatically invalidated** whenever tags are created, updated, or deleted.

```php
// config/laravel-turbo-tags.php
'cache' => [
    'enabled' => true,
    'ttl' => 3600,             // Cache lifetime in seconds (1 hour)
    'store' => null,            // null = your app's default cache driver
    'key_prefix' => 'turbo_tags',
],
```

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

## ⚙️ Configuration

After publishing the configuration file, you'll find it at `config/laravel-turbo-tags.php`:

<details>
<summary><strong>Full configuration reference</strong></summary>

| Key | Default | Description |
| --- | --- | --- |
| **General** | | |
| `tag_model` | `Tag::class` | Custom Tag model class |
| `tables.tags` | `'tags'` | Tags table name |
| `tables.taggables` | `'taggables'` | Pivot table name |
| **Locale** | | |
| `locale.primary` | `null` | Primary locale (null = app locale) |
| `locale.fallback` | `null` | Fallback locale |
| **Slugger** | | |
| `slugger.source` | `'name'` | Field to generate slug from |
| `slugger.generate_on_create` | `true` | Auto-generate slug on creation |
| `slugger.generate_unique` | `true` | Append `-2`, `-3` for uniqueness |
| **Cache** | | |
| `cache.enabled` | `false` | Enable caching (set `true` in production) |
| `cache.ttl` | `3600` | Cache lifetime in seconds |
| `cache.store` | `null` | Cache store (null = default driver) |
| `cache.key_prefix` | `'turbo_tags'` | Cache key prefix |
| **Performance** | | |
| `performance.chunk_size` | `1000` | Chunk size for bulk operations |
| **Suggestions** | | |
| `suggestions.limit` | `10` | Max suggestions returned |
| `suggestions.min_length` | `2` | Min characters before suggestions activate |

</details>

---

## 📋 Database Schema

TurboTags creates two tables:

<details>
<summary><strong>tags table</strong></summary>

| Column | Type | Description |
| --- | --- | --- |
| `id` | bigint (PK) | Primary key |
| `name` | JSON | Tag name — stores translations as `{"en": "Tech", "ar": "تقنية"}` |
| `slug` | string (unique) | Auto-generated URL-friendly identifier |
| `type` | string (nullable, indexed) | Tag category |
| `order_column` | integer (nullable) | Optional manual sort order |
| `is_active` | boolean (default: true) | Visibility flag |
| `metadata` | JSON (nullable) | Arbitrary JSON data |
| `owner_type` | string (nullable) | Polymorphic owner model class |
| `owner_id` | bigint (nullable) | Polymorphic owner model ID |
| `parent_id` | bigint (nullable, indexed) | Self-referencing FK for hierarchy |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |
| `deleted_at` | timestamp (nullable) | Soft delete timestamp |

</details>

<details>
<summary><strong>taggables table (pivot)</strong></summary>

| Column | Type | Description |
| --- | --- | --- |
| `id` | bigint (PK) | Primary key |
| `tag_id` | bigint (FK) | References `tags.id` — cascades on delete |
| `taggable_type` | string | The tagged model's class name |
| `taggable_id` | bigint | The tagged model's ID |
| `created_at` | timestamp | When the tag was attached |
| `updated_at` | timestamp | Last update |

A unique constraint on `(tag_id, taggable_id, taggable_type)` prevents duplicate tag attachments.

</details>

---

## ❓ FAQ

<details>
<summary><strong>Does this require a lot of configuration?</strong></summary>

No. Install, migrate, add the `HasTags` trait — that's it. TurboTags works with sensible defaults out of the box. It automatically creates tags on the fly, generates slugs, and handles translations. You only configure things when you want to change the defaults.
</details>

<details>
<summary><strong>Will this slow down my app?</strong></summary>

No. Tag operations are standard Eloquent queries. For high-traffic apps, enable the built-in cache layer (`cache.enabled = true`) to eliminate repeated database lookups. Batch operations like `findOrCreateMany` use deferred cache flushing — only one flush at the end instead of one per tag.
</details>

<details>
<summary><strong>Do I need a translation package for multilingual tags?</strong></summary>

No. TurboTags stores translations natively as JSON in the database. No `spatie/laravel-translatable` or any other package is needed. The fallback chain (requested locale → primary locale → app locale → fallback locale → first available) ensures users always see a meaningful name.
</details>

<details>
<summary><strong>Can tags be shared across different models?</strong></summary>

Yes. TurboTags uses Laravel's polymorphic `morphToMany` relationships. A `Post`, a `Product`, and a `User` can all share the same tag pool. You can also separate them by type if needed.
</details>

<details>
<summary><strong>How do I prevent circular references in hierarchical tags?</strong></summary>

You don't need to — TurboTags handles it automatically. Setting a tag's parent to itself or to one of its own descendants throws `InvalidArgumentException`. This protection works even through soft-deleted tags, with a depth limit of 100 to prevent stack overflow.
</details>

<details>
<summary><strong>What's the difference between is_active and soft deletes?</strong></summary>

`is_active` is for **visibility control** — toggle a tag off without removing it. Inactive tags are excluded from `findOrCreate`, `suggestions`, and query scopes like `withAllTags`. Soft deletes are for **actual deletion** — the tag is removed but can be restored. Use `is_active` for temporary hiding, soft deletes for permanent removal.
</details>

<details>
<summary><strong>Can I use this in a multi-tenant application?</strong></summary>

Yes. Use tag ownership to scope tags per user or team:

```php
Tag::global()->get();             // Shared tags
Tag::ownedBy($user)->get();       // User's private tags
Tag::availableTo($user)->get();   // Both
```
</details>

<details>
<summary><strong>Does it work with BackedEnum for tag types?</strong></summary>

Yes. All methods that accept a `$type` parameter also accept a `BackedEnum`. This gives you type-safety and IDE autocomplete instead of magic strings:

```php
enum TagType: string {
    case Language = 'language';
}

$post->attachTag('PHP', TagType::Language);
```
</details>

<details>
<summary><strong>How do I handle tag storage growth?</strong></summary>

Several options: (1) Use soft deletes to remove unused tags. (2) Use `is_active = false` to hide deprecated tags. (3) Set up cache with a reasonable TTL. (4) Use `findOrCreateMany` for batch operations to minimize queries. Tags are lightweight — a typical tag row is under 1KB.
</details>

---

## 🧪 Testing & Quality

```bash
composer test           # Pest test suite
composer test-coverage  # With code coverage
composer analyse        # PHPStan (level max)
composer format         # Laravel Pint
```

| PHP | Laravel | Status |
| --- | --- | --- |
| 8.2 | 11 / 12 / 13 | Supported |
| 8.3 | 11 / 12 / 13 | Supported |
| 8.4 | 11 / 12 / 13 | Supported |

---

## 🔐 Security

Please review [our security policy](SECURITY.md) on how to report security vulnerabilities. **Do not** open a public issue.

---

## 📄 Credits & License

- [LaraArabDev](https://github.com/LaraArabDev)
- [All Contributors](../../contributors)

MIT License — see [LICENSE](LICENSE.md) for details.

---

## 🇸🇦 العربية

<div dir="rtl" align="right">

### ما هو TurboTags؟

**TurboTags** حزمة Laravel تمنح تطبيقك نظام وسوم كامل بدون أي اعتمادات خارجية. ثبّت الحزمة، أضف trait واحد، وكل موديل يمكن وسمه فوراً مع أسماء متعددة اللغات، أشجار هرمية، تصنيف بالأنواع، ونطاقات ملكية.

لكن الوسوم البسيطة هي البداية فقط. يمكن لـ TurboTags أيضاً:

- **ترجمة مدمجة** — أسماء الوسوم تُخزّن كـ JSON بدعم لغات غير محدود. لا حاجة لحزمة ترجمة خارجية.
- **وسوم هرمية** — أنشئ أشجار وسوم مثل `برمجة > PHP > Laravel`. تنقّل بـ `ancestors()` و `descendants()`.
- **أنواع الوسوم** — صنّف الوسوم باستخدام نصوص بسيطة أو PHP `BackedEnum` الآمن.
- **ملكية الوسوم** — وسوم عامة، أو ملك مستخدم، أو خاصة بفريق. مثالي للتطبيقات متعددة المستأجرين.
- **تحليلات مدمجة** — الوسوم الأكثر شعبية، الرائجة، والمُنشأة حديثاً.
- **تخزين مؤقت ذكي** — طبقة تخزين مؤقت مع إبطال تلقائي.

### التثبيت

```bash
composer require laraarabdev/laravel-turbo-tags
php artisan vendor:publish --tag="laravel-turbo-tags-config"
php artisan vendor:publish --tag="laravel-turbo-tags-migrations"
php artisan migrate
```

### البداية السريعة

```php
use LaraArabDev\TurboTags\Concerns\HasTags;

class Post extends Model
{
    use HasTags;  // يُفعّل الوسوم تلقائياً
}
```

```php
// إضافة وسوم — تُنشأ تلقائيًا إن لم تكن موجودة
$post->attachTags(['Laravel', 'PHP', 'Open Source']);

// التحقق من الوسوم
$post->hasTag('Laravel'); // true

// البحث عن موديلات بوسوم محددة
Post::withAllTags(['Laravel', 'PHP'])->get();

// وسوم متعددة اللغات
$tag = Tag::create([
    'name' => ['en' => 'Technology', 'ar' => 'تقنية'],
]);
```

### الميزات الرئيسية

| الميزة | الوصف |
| --- | --- |
| **وسوم متعددة الأشكال** | أضف وسومًا لأي موديل Eloquent. نظام وسوم واحد لتطبيقك بالكامل. |
| **ترجمة مدمجة** | أسماء وسوم متعددة اللغات مخزنة كـ JSON — بدون حزم ترجمة خارجية. |
| **وسوم هرمية** | بنية شجرية أب-ابن مع تحميل متلهف متكرر. |
| **أنواع الوسوم** | صنّف الوسوم بنصوص عادية أو PHP `BackedEnum` الآمن. |
| **ملكية الوسوم** | وسوم عامة، لكل مستخدم، أو لكل فريق. |
| **نطاقات الاستعلام** | `withAllTags`، `withAnyTags`، `withoutTags`، `withTagsOfType`، `withTagCount`. |
| **تقارير الوسوم** | `mostPopular()`، `recent()`، `recentMostPopular()`. |
| **روابط نصية تلقائية** | Slugs صديقة للروابط تُولّد تلقائيًا مع ضمان التفرد. |
| **اقتراحات الوسوم** | بحث أثناء الكتابة مع حد أدنى قابل للإعداد. |
| **تخزين مؤقت ذكي** | إبطال تلقائي عند الإنشاء/التحديث/الحذف. |
| **بيانات وصفية** | أرفق بيانات JSON عشوائية بأي وسم — ألوان، أيقونات، أوصاف. |
| **حماية من المراجع الدائرية** | تمنع دورات أب-ابن غير صالحة تلقائيًا. |

### الاستخدام

```php
// إضافة وإزالة الوسوم
$post->attachTags(['Laravel', 'PHP']);
$post->detachTag('PHP');
$post->syncTags(['Laravel', 'Go']);
$post->removeAllTags();

// التحقق
$post->hasTag('Laravel');               // true
$post->hasAllTags(['Laravel', 'Go']);   // true
$post->hasAnyTags(['Laravel', 'Ruby']); // true

// نطاقات الاستعلام
Post::withAllTags(['Laravel'])->get();
Post::withAnyTags(['Laravel', 'Python'])->get();
Post::withoutTags(['Deprecated'])->get();
Post::withTagsOfType('language')->get();
```

### الوسوم الهرمية

```php
$programming = Tag::create(['name' => ['en' => 'Programming', 'ar' => 'برمجة']]);
$php = Tag::create(['name' => ['en' => 'PHP'], 'parent_id' => $programming->id]);

$php->parent;              // → وسم Programming
$programming->children;    // → مجموعة [PHP]
$php->ancestors();         // مجموعة: [Programming]
$programming->descendants(); // مجموعة: [PHP]
```

### أنواع الوسوم و Enums

```php
enum TagType: string {
    case Language = 'language';
    case Framework = 'framework';
}

$post->attachTag('Laravel', TagType::Framework);
$post->syncTagsWithType(['PHP', 'Go'], TagType::Language);
Tag::ofType(TagType::Language)->get();
```

### التقارير والتحليلات

```php
Tag::mostPopular(10);                        // أكثر 10 وسوم استخدامًا
Tag::recent(10);                             // أحدث 10 وسوم
Tag::recentMostPopular(10, 30);              // أكثر 10 في آخر 30 يوم
Tag::recentMostPopular(5, 7, 'language');     // أكثر 5 لغات في آخر 7 أيام
```

### الأسئلة الشائعة

<details>
<summary><strong>هل يحتاج إعداد كثير؟</strong></summary>

لا. ثبّت، نفّذ التهجير، أضف `HasTags` trait — هذا كل شيء. يعمل بإعدادات ذكية افتراضياً.
</details>

<details>
<summary><strong>هل يبطئ تطبيقي؟</strong></summary>

لا. عمليات الوسوم هي استعلامات Eloquent عادية. فعّل التخزين المؤقت المدمج لتسريع البحث المتكرر.
</details>

<details>
<summary><strong>هل أحتاج حزمة ترجمة للوسوم متعددة اللغات؟</strong></summary>

لا. TurboTags يخزّن الترجمات أصلياً كـ JSON في قاعدة البيانات. لا حاجة لـ `spatie/laravel-translatable` أو أي حزمة أخرى.
</details>

<details>
<summary><strong>هل تُشارك الوسوم بين موديلات مختلفة؟</strong></summary>

نعم. يستخدم TurboTags علاقات Laravel متعددة الأشكال (`morphToMany`). المقال والمنتج والمستخدم يمكنهم مشاركة نفس مجموعة الوسوم.
</details>

<details>
<summary><strong>هل يعمل في تطبيقات متعددة المستأجرين؟</strong></summary>

نعم. استخدم ملكية الوسوم لتحديد النطاق لكل مستخدم أو فريق:

```php
Tag::global()->get();             // وسوم مشتركة
Tag::ownedBy($user)->get();       // وسوم المستخدم الخاصة
Tag::availableTo($user)->get();   // كلاهما
```
</details>

---

للتوثيق الكامل ومرجع API التفصيلي، راجع الأقسام الإنجليزية أعلاه.

</div>
