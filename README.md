<p align="center">
    <img src="art/banner.svg" alt="TurboTags Banner" style="width: 100%; max-width: 800px;">
</p>

<h1 align="center">TurboTags</h1>

<p align="center">
    <strong>Translatable, hierarchical, polymorphic tags for Laravel</strong><br>
    <strong>وسوم متعددة اللغات وهرمية ومتعددة الأشكال لـ Laravel</strong>
</p>

<p align="center">
    <a href="https://packagist.org/packages/laraarabdev/laravel-turbo-tags"><img src="https://img.shields.io/packagist/v/laraarabdev/laravel-turbo-tags.svg?style=flat-square" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/laraarabdev/laravel-turbo-tags"><img src="https://img.shields.io/packagist/dt/laraarabdev/laravel-turbo-tags.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laraarabdev/laravel-turbo-tags"><img src="https://img.shields.io/packagist/l/laraarabdev/laravel-turbo-tags.svg?style=flat-square" alt="License"></a>
    <a href="https://packagist.org/packages/laraarabdev/laravel-turbo-tags"><img src="https://img.shields.io/packagist/php-v/laraarabdev/laravel-turbo-tags.svg?style=flat-square" alt="PHP"></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/laravel-11.x%20%7C%2012.x-red?style=flat-square" alt="Laravel"></a>
</p>

<p align="center">
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/tests.yml?branch=main&label=tests&style=flat-square" alt="Tests"></a>
    <a href="https://codecov.io/gh/LaraArabDev/laravel-turbo-tags"><img src="https://img.shields.io/codecov/c/github/LaraArabDev/laravel-turbo-tags?style=flat-square" alt="codecov"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/static-analysis.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/static-analysis.yml?branch=main&label=phpstan&style=flat-square" alt="Static Analysis"></a>
    <a href="https://github.com/LaraArabDev/laravel-turbo-tags/actions/workflows/code-style.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/laravel-turbo-tags/code-style.yml?branch=main&label=pint&style=flat-square" alt="Code Style"></a>
</p>

<p align="center">
    PHP 8.2+ &middot; Laravel 11 / 12 &middot; 95%+ Test Coverage &middot; PHPStan Level Max
</p>

<p align="center">
    <b><a href="https://github.com/LaraArabDev">LaraArabDev</a></b> — We build, develop, empower, and contribute. An Arab open-source community crafting production-grade Laravel packages.<br>
    <b><a href="https://github.com/LaraArabDev">LaraArabDev</a></b> — نبني، نطوّر، نُمكّن، ونُساهم. مجتمع عربي مفتوح المصدر يصنع حزم Laravel احترافية وجاهزة للإنتاج.
</p>

<p align="center">
    <a href="#-quick-start">Quick Start</a> &middot;
    <a href="#-why-turbotags">Why TurboTags</a> &middot;
    <a href="#-features">Features</a> &middot;
    <a href="#-usage">Usage</a> &middot;
    <a href="#%EF%B8%8F-configuration">Configuration</a> &middot;
    <a href="#-testing">Testing</a> &middot;
    <a href="#-arabic-documentation">عربي</a>
</p>

---

## What is TurboTags?

**TurboTags** is a complete tagging solution for Laravel. Tag any Eloquent model — posts, products, users, anything — with tags that support **multiple languages**, **parent-child hierarchies**, **typed categories**, **ownership scoping**, and **smart caching**.

Unlike other tagging packages, TurboTags ships with **built-in translation support** (no need for `spatie/laravel-translatable` or any translation package), **hierarchical tag trees** with circular reference protection, and a **configurable cache layer** that auto-invalidates — all in a single, zero-dependency package.

### ما هو TurboTags؟

**TurboTags** هو حل متكامل للوسوم في Laravel. أضف وسومًا لأي موديل Eloquent — مقالات، منتجات، مستخدمين، أي شيء — مع دعم **تعدد اللغات**، **الهيكلة الشجرية (أب-ابن)**، **التصنيف حسب النوع**، **ملكية الوسوم**، و**التخزين المؤقت الذكي**. لا يحتاج أي حزمة ترجمة خارجية — الترجمة مدمجة.

---

## 🚀 Quick Start

```bash
composer require laraarabdev/laravel-turbo-tags
```

> **No external dependencies required.** Translations, slugs, caching — everything is built in.
>
> **لا يحتاج أي حزمة خارجية.** الترجمة، الروابط النصية، التخزين المؤقت — كل شيء مدمج.

Publish the config and migrations:

```bash
php artisan vendor:publish --tag="laravel-turbo-tags-config"
php artisan vendor:publish --tag="laravel-turbo-tags-migrations"
php artisan migrate
```

Add the trait to any model:

```php
use LaraArabDev\TurboTags\Concerns\HasTags;

class Post extends Model
{
    use HasTags;
}
```

Start tagging:

```php
$post->attachTags(['Laravel', 'PHP', 'Open Source']);
```

That's it. You're ready.

| Requirement | Version |
| --- | --- |
| PHP | 8.2+ |
| Laravel | 11 or 12 |
| External packages | **None** |

---

## 💡 Why TurboTags?

### Built-in translations — no extra packages

Other tagging packages require `spatie/laravel-translatable` for multilingual support. TurboTags stores translations natively as JSON with a smart fallback chain: **requested locale → primary locale → app locale → fallback locale → first available**. Zero configuration for single-language apps, full power for multilingual ones.

```php
$tag = Tag::create([
    'name' => ['en' => 'Technology', 'ar' => 'تقنية', 'fr' => 'Technologie'],
]);

$tag->getTranslatedName('ar'); // "تقنية"
$tag->getTranslatedName();     // Uses your app locale automatically
```

### Hierarchical tags out of the box

Build tag trees like `Programming → PHP → Laravel`. Load entire subtrees with a single eager-loaded query. Navigate up with `ancestors()`, down with `descendants()`. Circular references are automatically prevented at the database and model level.

### Type-safe with PHP enums

Categorize tags using native PHP `BackedEnum` — no magic strings:

```php
enum TagType: string {
    case Language = 'language';
    case Framework = 'framework';
}

$post->attachTag('Laravel', TagType::Framework);
```

### Production-tested quality

- **95%+ code coverage** across 101 tests
- **PHPStan level max** — zero errors
- **Pint-clean** code style
- **Soft deletes**, **cache invalidation**, **circular reference protection**

---

## 📦 Features

| Feature | Description |
| --- | --- |
| **Polymorphic tagging** | Tag any Eloquent model via `morphToMany` |
| **Built-in translations** | Multilingual tag names stored as JSON — no extra packages needed |
| **Hierarchical tags** | Parent-child tree structure with recursive eager loading |
| **Tag types** | Categorize tags (e.g., `language`, `framework`) with string or `BackedEnum` |
| **Tag ownership** | Scope tags globally, per user, or per team |
| **Query scopes** | `withAllTags`, `withAnyTags`, `withoutTags`, `withTagsOfType` |
| **Auto slug generation** | URL-friendly slugs with uniqueness enforcement |
| **Tag suggestions** | Search-as-you-type with configurable min length and limit |
| **Smart caching** | Configurable cache layer with automatic invalidation on changes |
| **Sync operations** | Sync tags globally or per type, preserving other types |
| **Metadata** | Attach arbitrary JSON data to any tag |
| **Ordering** | Optional `order_column` for custom sort order |
| **Circular reference protection** | Prevents invalid parent-child cycles, even through soft-deleted tags |
| **Soft deletes** | Tags support soft deleting with `nullOnDelete` for children |
| **Batch operations** | `findOrCreateMany` with deferred cache flushing for performance |

---

## 📖 Usage

### Attaching & detaching tags

```php
// By name — auto-creates tags if they don't exist
$post->attachTag('Laravel');
$post->attachTags(['Laravel', 'PHP', 'Testing']);

// By model instance or ID
$tag = Tag::findOrCreate('Laravel');
$post->attachTag($tag);
$post->attachTag($tag->id);

// With a type
$post->attachTag('PHP', 'language');
$post->attachTag('Laravel', 'framework');

// Detach
$post->detachTag('Laravel');
$post->detachTags(['Laravel', 'PHP']);
$post->removeAllTags();
```

### Syncing tags

```php
// Replace all tags on the model
$post->syncTags(['Laravel', 'PHP']);

// Replace only tags of a specific type (other types are preserved)
$post->syncTagsWithType(['Python', 'Ruby'], 'language');
```

### Checking tags

```php
$post->hasTag('Laravel');                // true / false
$post->hasAllTags(['Laravel', 'PHP']);   // true if ALL are present
$post->hasAnyTags(['Laravel', 'Ruby']); // true if ANY is present
```

### Query scopes

```php
// Models tagged with ALL of these
Post::withAllTags(['Laravel', 'PHP'])->get();

// Models tagged with ANY of these
Post::withAnyTags(['Laravel', 'Python'])->get();

// Models NOT tagged with these
Post::withoutTags(['Deprecated'])->get();

// Models with tags of a specific type
Post::withTagsOfType('language')->get();

// Eager load tags / tag count
Post::withTagsLoaded()->get();
Post::withTagCount()->get();
```

---

### 🌳 Hierarchical Tags

Build tag trees with parent-child relationships. Useful for categories, taxonomies, nested navigation, and more.

```php
// Create a tree: Programming → PHP → Laravel
$programming = Tag::create(['name' => ['en' => 'Programming', 'ar' => 'برمجة']]);
$php = Tag::create(['name' => ['en' => 'PHP'], 'parent_id' => $programming->id]);
$laravel = Tag::create(['name' => ['en' => 'Laravel'], 'parent_id' => $php->id]);

// Navigate the tree
$laravel->parent;               // → PHP tag
$programming->children;         // → [PHP]
$laravel->isLeaf();             // true (no children)
$programming->isRoot();         // true (no parent)

// Load entire subtree in one query
$programming->load('childrenRecursive');

// Get all ancestors (bottom → top)
$laravel->ancestors();          // [PHP, Programming]

// Get all descendants (flattened)
$programming->descendants();    // [PHP, Laravel]

// Query root tags only
Tag::roots()->get();
```

**Circular references are prevented automatically.** Setting a tag's parent to itself or to one of its own descendants throws `InvalidArgumentException`. This protection works even through soft-deleted tags.

---

### 🌍 Translations (Built-in — No Extra Packages)

> **Important:** TurboTags does **NOT** require `spatie/laravel-translatable` or any translation package. Translations are built into the package using JSON columns with a smart fallback chain.
>
> **ملاحظة مهمة:** لا يحتاج TurboTags إلى حزمة `spatie/laravel-translatable` أو أي حزمة ترجمة أخرى. الترجمة مدمجة في الحزمة باستخدام أعمدة JSON مع سلسلة بديلة ذكية.

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

// Check and list
$tag->hasTranslation('ar');     // true
$tag->getTranslations();        // ['en' => 'Technology', 'ar' => 'تقنية', ...]
```

**Fallback chain:** requested locale → config `locale.primary` → `app()->getLocale()` → config `locale.fallback` → first available translation. Your users always see something meaningful.

---

### 👤 Tag Ownership

Scope tags to specific users, teams, or any model. Perfect for user-generated tags or multi-tenant apps.

```php
// Create a user-owned tag
$tag = Tag::create([
    'name' => ['en' => 'Favorites'],
    'owner_type' => User::class,
    'owner_id' => $user->id,
]);

// Query scopes
Tag::global()->get();               // Tags with no owner
Tag::ownedBy($user)->get();         // Tags belonging to this user
Tag::availableTo($user)->get();     // Global + this user's tags
```

---

### 🏷️ Tag Types & Enums

Organize tags into categories using strings or type-safe PHP enums:

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

### 🔍 Tag Suggestions

Build autocomplete / search-as-you-type with built-in suggestion support:

```php
$suggestions = Tag::suggestions('Lara');                    // Tags containing "Lara"
$suggestions = Tag::suggestions('PH', 'language', 'en', 5); // With type, locale, limit
```

Minimum search length is configurable to prevent overly broad queries.

---

### ⚡ Caching

Enable the built-in cache layer for high-traffic apps. Cache is **automatically invalidated** whenever tags are created, updated, or deleted — no manual flushing needed.

```php
// config/laravel-turbo-tags.php
'cache' => [
    'enabled' => true,
    'ttl' => 3600,             // seconds
    'store' => null,            // null = default cache store
    'key_prefix' => 'turbo_tags',
],
```

Cached methods: `findOrCreate`, `allCached`, `allOfTypeCached`, `suggestions`.

```php
// Get all tags from cache
$tags = Tag::allCached();
$tags = Tag::allOfTypeCached('language');

// Batch operations use deferred flushing (1 flush instead of N)
$tags = Tag::findOrCreateMany(['PHP', 'Laravel', 'Go']);

// Manual flush if needed
Tag::flushTagCache();
```

---

### 📦 findOrCreate

Efficiently find or create tags — with caching and batch support:

```php
// Single tag
$tag = Tag::findOrCreate('Laravel', 'framework', 'en');

// Batch — 1 query to find existing + creates for missing, 1 cache flush total
$tags = Tag::findOrCreateMany(['PHP', 'Laravel', 'Testing'], 'framework', 'en');
```

---

## ⚙️ Configuration

After publishing (`php artisan vendor:publish --tag="laravel-turbo-tags-config"`):

```php
return [
    // Custom tag model (extend Tag if you need custom behavior)
    'tag_model' => \LaraArabDev\TurboTags\Models\Tag::class,

    // Table names (customize if they conflict with your app)
    'tables' => [
        'tags' => 'tags',
        'taggables' => 'taggables',
    ],

    // Locale settings — null uses app locale
    'locale' => [
        'primary' => null,       // e.g., 'en' or 'ar'
        'fallback' => null,      // e.g., 'en'
    ],

    // Slug generation from tag names
    'slugger' => [
        'source' => 'name',
        'generate_on_create' => true,   // Auto-generate slug on create
        'generate_unique' => true,      // Append -2, -3, etc. for uniqueness
    ],

    // Performance caching
    'cache' => [
        'enabled' => false,      // Enable in production for speed
        'ttl' => 3600,           // Cache lifetime in seconds
        'store' => null,         // null = default cache driver
        'key_prefix' => 'turbo_tags',
    ],

    // Performance tuning
    'performance' => [
        'chunk_size' => 1000,
    ],

    // Suggestion / autocomplete settings
    'suggestions' => [
        'limit' => 10,           // Max suggestions returned
        'min_length' => 2,       // Min search string length
    ],
];
```

---

## 🧪 Testing

The package has **101 tests** with **95%+ code coverage** and passes **PHPStan at max level**.

```bash
# Run tests
composer test

# Run tests with coverage report
composer test-coverage

# Static analysis (PHPStan max level)
composer analyse

# Code style check (Laravel Pint)
composer format-test

# Fix code style
composer format
```

---

## 📋 API Reference

### `HasTags` Trait Methods

| Method | Description |
| --- | --- |
| `attachTag($tags, $type, $locale)` | Attach one or more tags |
| `attachTags($tags, $type, $locale)` | Alias for `attachTag` |
| `detachTag($tags, $type, $locale)` | Detach one or more tags |
| `detachTags($tags, $type, $locale)` | Alias for `detachTag` |
| `syncTags($tags, $type, $locale)` | Replace all tags (or per type) |
| `syncTagsWithType($tags, $type, $locale)` | Sync tags of a specific type |
| `removeAllTags()` | Remove all tags from the model |
| `hasTag($tag, $type, $locale)` | Check if model has a tag |
| `hasAllTags($tags, $type, $locale)` | Check if model has all given tags |
| `hasAnyTags($tags, $type, $locale)` | Check if model has any of the given tags |

### `Tag` Model Methods

| Method | Description |
| --- | --- |
| `Tag::findOrCreate($name, $type, $locale)` | Find or create a single tag |
| `Tag::findOrCreateMany($names, $type, $locale)` | Batch find or create |
| `Tag::allCached()` | Get all tags (cached) |
| `Tag::allOfTypeCached($type)` | Get tags by type (cached) |
| `Tag::suggestions($search, $type, $locale, $limit)` | Search tag suggestions |
| `Tag::flushTagCache()` | Flush all cached tags |
| `Tag::roots()` | Query scope: root tags only |
| `Tag::global()` | Query scope: unowned tags |
| `Tag::ownedBy($model)` | Query scope: tags owned by model |
| `Tag::availableTo($model)` | Query scope: global + owned |
| `Tag::ofType($type)` | Query scope: filter by type |
| `Tag::containing($search, $locale)` | Query scope: name contains |
| `Tag::ordered($direction)` | Query scope: order by column |
| `Tag::withSlug($slug)` | Query scope: find by slug |

### Tag Instance Methods

| Method | Description |
| --- | --- |
| `$tag->parent` | Get parent tag |
| `$tag->children` | Get direct children |
| `$tag->childrenRecursive` | Get full subtree (eager-loadable) |
| `$tag->ancestors()` | Get all ancestors (bottom-up) |
| `$tag->descendants()` | Get all descendants (flattened) |
| `$tag->isRoot()` | Has no parent? |
| `$tag->isLeaf()` | Has no children? |
| `$tag->getTranslatedName($locale)` | Get translated name |
| `$tag->setTranslatedName($value, $locale)` | Set translation |
| `$tag->getTranslations()` | Get all translations |
| `$tag->hasTranslation($locale)` | Check if translation exists |
| `$tag->taggedModels(Model::class)` | Get models tagged with this tag |

---

## 🌐 Arabic Documentation

<div dir="rtl">

### التوثيق بالعربية

**TurboTags** هو حزمة وسوم شاملة لـ Laravel تتميز بالآتي:

#### المميزات الرئيسية

- **وسوم متعددة الأشكال** — أضف وسومًا لأي موديل Eloquent
- **ترجمة مدمجة** — لا تحتاج أي حزمة خارجية مثل Spatie. الأسماء تُخزّن كـ JSON متعدد اللغات
- **وسوم هرمية** — علاقات أب-ابن مع حماية من المراجع الدائرية
- **أنواع الوسوم** — صنّف الوسوم حسب النوع (مثلاً: لغة، إطار عمل، فئة)
- **ملكية الوسوم** — وسوم عامة أو خاصة بمستخدم أو فريق
- **تخزين مؤقت ذكي** — مع إبطال تلقائي عند أي تغيير
- **اقتراحات بحث** — دعم البحث أثناء الكتابة
- **روابط نصية تلقائية (Slugs)** — مع ضمان التفرد

#### التثبيت

</div>

```bash
composer require laraarabdev/laravel-turbo-tags
php artisan vendor:publish --tag="laravel-turbo-tags-config"
php artisan vendor:publish --tag="laravel-turbo-tags-migrations"
php artisan migrate
```

<div dir="rtl">

#### الاستخدام الأساسي

</div>

```php
use LaraArabDev\TurboTags\Concerns\HasTags;

class Post extends Model
{
    use HasTags;
}

// إضافة وسوم
$post->attachTags(['Laravel', 'PHP']);

// إنشاء وسم بترجمات متعددة
$tag = Tag::create([
    'name' => ['en' => 'Technology', 'ar' => 'تقنية'],
]);

// الحصول على الترجمة
$tag->getTranslatedName('ar'); // "تقنية"

// إنشاء شجرة وسوم
$programming = Tag::create(['name' => ['ar' => 'برمجة']]);
$php = Tag::create(['name' => ['ar' => 'بي إتش بي'], 'parent_id' => $programming->id]);

// البحث عن وسوم جذرية فقط
Tag::roots()->get();

// الوسوم التابعة
$programming->descendants(); // [بي إتش بي]
```

<div dir="rtl">

#### إعداد اللغة العربية كلغة أساسية

</div>

```php
// config/laravel-turbo-tags.php
'locale' => [
    'primary' => 'ar',
    'fallback' => 'en',
],
```

<div dir="rtl">

بهذا الإعداد، سيُرجع <code>getTranslatedName()</code> الترجمة العربية تلقائيًا بدون تمرير اللغة.

</div>

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
    <sub>Built with &#10084; by <a href="https://github.com/LaraArabDev">LaraArabDev</a> — An Arab open-source community</sub><br>
    <sub>صُنع بـ &#10084; بواسطة <a href="https://github.com/LaraArabDev">LaraArabDev</a> — مجتمع عربي مفتوح المصدر</sub>
</p>
