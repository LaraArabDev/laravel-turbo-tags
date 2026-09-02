<div dir="rtl">

# TurboTags - التوثيق الشامل بالعربية

<p align="center">
    <img src="art/banner.svg" alt="TurboTags Banner" style="width: 100%; max-width: 800px;">
</p>

<h1 align="center">TurboTags</h1>

<p align="center">
    <strong>وسوم متعددة اللغات وهرمية ومتعددة الأشكال لـ Laravel</strong><br>
    <em>Translatable, hierarchical, polymorphic tags for Laravel</em>
</p>

---

## ما هو TurboTags؟

**TurboTags** هو حل متكامل وشامل للوسوم في Laravel. يسمح لك بإضافة وسوم إلى أي موديل Eloquent — مقالات، منتجات، مستخدمين، أي شيء — مع دعم كامل لـ:

- **تعدد اللغات**: الترجمة مدمجة (بدون الحاجة لـ `spatie/laravel-translatable`)
- **الهيكلة الشجرية (الهرمية)**: علاقات أب-ابن مع حماية من المراجع الدائرية
- **التصنيف حسب النوع**: صنّف الوسوم بأنواع مختلفة
- **ملكية الوسوم**: وسوم عامة أو خاصة بمستخدم معين أو فريق
- **التخزين المؤقت الذكي**: مع إبطال تلقائي عند أي تغيير
- **البحث والاقتراحات**: دعم البحث أثناء الكتابة

كل هذا في حزمة واحدة بدون اعتماديات خارجية!

---

## 🚀 البدء السريع

### التثبيت

```bash
composer require laraarabdev/laravel-turbo-tags
```

### نشر الملفات

```bash
php artisan vendor:publish --tag="laravel-turbo-tags-config"
php artisan vendor:publish --tag="laravel-turbo-tags-migrations"
php artisan migrate
```

### إضافة الميزة إلى الموديل

```php
use LaraArabDev\TurboTags\Concerns\HasTags;

class Post extends Model
{
    use HasTags;
}
```

### البدء في الاستخدام

```php
// إضافة وسم واحد
$post->attachTag('لاراڤل');

// إضافة عدة وسوم
$post->attachTags(['لاراڤل', 'PHP', 'مفتوح المصدر']);
```

هذا كل ما تحتاجه!

---

## 💡 لماذا TurboTags؟

### 1. الترجمة مدمجة (لا توجد حزم إضافية)

حزم الوسوم الأخرى تتطلب `spatie/laravel-translatable` للدعم متعدد اللغات.

**TurboTags يختلف تماماً**: الترجمة مخزنة بشكل أصلي كـ JSON مع سلسلة بديلة ذكية:
**اللغة المطلوبة → اللغة الأساسية → لغة التطبيق → اللغة الاحتياطية → أول ترجمة متاحة**

```php
// إنشاء وسم بترجمات متعددة
$tag = Tag::create([
    'name' => ['en' => 'Technology', 'ar' => 'تقنية', 'fr' => 'Technologie'],
]);

// الحصول على الترجمة
$tag->getTranslatedName('ar');  // "تقنية"
$tag->getTranslatedName('en');  // "Technology"
$tag->getTranslatedName();      // يستخدم لغة التطبيق الحالية تلقائياً
```

### 2. وسوم هرمية جاهزة

بناء أشجار وسوم مثل `برمجة → PHP → لاراڤل`. حمّل الشجرة بأكملها بـ استعلام واحد محسّن.

```php
// إنشاء شجرة
$programming = Tag::create(['name' => ['ar' => 'برمجة']]);
$php = Tag::create(['name' => ['ar' => 'PHP'], 'parent_id' => $programming->id]);
$laravel = Tag::create(['name' => ['ar' => 'لاراڤل'], 'parent_id' => $php->id]);

// الملاحة في الشجرة
$laravel->parent;               // → وسم PHP
$programming->children;         // → [PHP]
$programming->descendants();    // → [PHP, لاراڤل]
$laravel->ancestors();          // → [PHP, برمجة]

// تحميل الشجرة بأكملها
$programming->load('childrenRecursive');
```

**حماية من المراجع الدائرية**: لا يمكنك جعل وسم والداً لنفسه أو لأحد أسلافه — المنع يحدث تلقائياً!

### 3. أنواع الوسوم (Type-Safe)

صنّف الوسوم باستخدام Enums الأصليين في PHP:

```php
enum TagType: string {
    case Language = 'language';
    case Framework = 'framework';
    case Category = 'category';
}

// إضافة وسم بنوع محدد
$post->attachTag('PHP', TagType::Language);
$post->attachTag('لاراڤل', TagType::Framework);

// الاستعلام حسب النوع
Tag::ofType(TagType::Language)->get();
Post::withTagsOfType(TagType::Framework)->get();
```

### 4. جودة متقدمة (Production-Ready)

- **95%+ تغطية اختبارات** عبر 101 اختبار
- **PHPStan المستوى الأقصى** — بدون أخطاء
- **كود نظيف** وفقاً لـ Laravel Pint
- **الحذف الناعم**، **إبطال التخزين المؤقت التلقائي**، **حماية المراجع الدائرية**

---

## 📦 المميزات الكاملة

| الميزة | الوصف |
| --- | --- |
| **وسوم متعددة الأشكال** | أضف وسوم إلى أي موديل Eloquent عبر `morphToMany` |
| **ترجمات مدمجة** | أسماء وسوم متعددة اللغات كـ JSON — بدون حزم إضافية |
| **وسوم هرمية** | هيكل شجري أب-ابن مع تحميل محسّن للعلاقات |
| **أنواع الوسوم** | تصنيف الوسوم (مثلاً: `language`, `framework`) باستخدام نصوص أو `BackedEnum` |
| **ملكية الوسوم** | وسوم عامة أو خاصة بمستخدم أو فريق |
| **نطاقات الاستعلام** | `withAllTags`, `withAnyTags`, `withoutTags`, `withTagsOfType` |
| **الروابط النصية التلقائية** | روابط صديقة لـ URL مع ضمان التفرد |
| **اقتراحات الوسوم** | بحث أثناء الكتابة مع حد أدنى قابل للتخصيص |
| **التخزين المؤقت الذكي** | مع إبطال تلقائي عند التغييرات |
| **عمليات المزامنة** | مزامنة الوسوم عامة أو حسب النوع |
| **بيانات إضافية** | ارفق بيانات JSON عشوائية بأي وسم |
| **الترتيب المخصص** | عمود `order_column` اختياري للترتيب |
| **الحذف الناعم** | الوسوم تدعم الحذف الناعم مع `nullOnDelete` للأطفال |
| **عمليات الدفعات** | `findOrCreateMany` مع إبطال تخزين مؤقت مؤجل |

---

## 📖 الاستخدام التفصيلي

### إضافة وحذف الوسوم

```php
// بالاسم — ينشئ الوسم تلقائياً إذا لم يكن موجوداً
$post->attachTag('لاراڤل');
$post->attachTags(['لاراڤل', 'PHP', 'اختبار']);

// بموديل أو معرّف
$tag = Tag::findOrCreate('لاراڤل');
$post->attachTag($tag);
$post->attachTag($tag->id);

// مع نوع محدد
$post->attachTag('PHP', 'language');
$post->attachTag('لاراڤل', 'framework');

// الحذف
$post->detachTag('لاراڤل');
$post->detachTags(['لاراڤل', 'PHP']);
$post->removeAllTags();
```

### مزامنة الوسوم

```php
// استبدال جميع الوسوم على الموديل
$post->syncTags(['لاراڤل', 'PHP']);

// استبدال وسوم نوع معين فقط (الأنواع الأخرى تبقى كما هي)
$post->syncTagsWithType(['بايثون', 'روبي'], 'language');
```

### التحقق من الوسوم

```php
$post->hasTag('لاراڤل');                    // true / false
$post->hasAllTags(['لاراڤل', 'PHP']);       // true إذا كانت جميعها موجودة
$post->hasAnyTags(['لاراڤل', 'روبي']);     // true إذا كان أي منها موجوداً
```

### نطاقات الاستعلام

```php
// موديلات لها جميع هذه الوسوم
Post::withAllTags(['لاراڤل', 'PHP'])->get();

// موديلات لها أي من هذه الوسوم
Post::withAnyTags(['لاراڤل', 'بايثون'])->get();

// موديلات ليس لها هذه الوسوم
Post::withoutTags(['متقادم'])->get();

// موديلات لها وسوم نوع معين
Post::withTagsOfType('language')->get();

// تحميل الوسوم بعلاقات محسّنة
Post::withTagsLoaded()->get();
Post::withTagCount()->get();
```

---

## 🌳 الوسوم الهرمية (التفاصيل)

### بناء شجرة كاملة

```php
// إنشاء الشجرة: برمجة → PHP → لاراڤل
$programming = Tag::create([
    'name' => ['ar' => 'برمجة', 'en' => 'Programming']
]);

$php = Tag::create([
    'name' => ['ar' => 'PHP', 'en' => 'PHP'],
    'parent_id' => $programming->id
]);

$laravel = Tag::create([
    'name' => ['ar' => 'لاراڤل', 'en' => 'Laravel'],
    'parent_id' => $php->id
]);
```

### الملاحة والاستعلام

```php
// الوالد والأطفال
$laravel->parent;               // → وسم PHP
$php->children;                 // → [لاراڤل]
$programming->children;         // → [PHP]

// الفحوصات
$laravel->isLeaf();             // true (بدون أطفال)
$programming->isRoot();         // true (بدون والد)
$php->isAncestorOf($laravel);   // true
$programming->isDescendantOf($laravel); // false

// الشجرة كاملة
$laravel->ancestors();          // [PHP, برمجة]
$programming->descendants();    // [PHP, لاراڤل]

// تحميل الشجرة بأكملها بعلاقة محسّنة
$programming->load('childrenRecursive');

// الاستعلام عن الوسوم الجذرية فقط
Tag::roots()->get();
```

---

## 🌍 الترجمات (مدمجة 100%)

**نقطة مهمة**: TurboTags **لا يحتاج** إلى `spatie/laravel-translatable`. الترجمات مدمجة باستخدام أعمدة JSON مع سلسلة بديلة ذكية.

### إنشاء وسوم بترجمات

```php
$tag = Tag::create([
    'name' => [
        'ar' => 'تقنية',
        'en' => 'Technology',
        'fr' => 'Technologie',
    ],
]);

$tag->getTranslatedName('ar');  // "تقنية"
$tag->getTranslatedName('en');  // "Technology"
$tag->getTranslatedName('fr');  // "Technologie"
```

### تحديث الترجمات

```php
// إضافة أو تحديث ترجمة
$tag->setTranslatedName('تقنية متقدمة', 'ar');
$tag->setTranslatedName('Advanced Technology', 'en');
$tag->save();

// الحصول على جميع الترجمات
$tag->getTranslations();
// ['ar' => 'تقنية', 'en' => 'Technology', 'fr' => 'Technologie']

// التحقق من وجود ترجمة
$tag->hasTranslation('ar');     // true
```

### سلسلة البديل الذكية

عندما تطلب ترجمة، TurboTags يبحث بالترتيب التالي:

1. اللغة المطلوبة مباشرة
2. اللغة الأساسية (من الإعدادات)
3. لغة التطبيق الحالية
4. اللغة الاحتياطية (من الإعدادات)
5. أول ترجمة متاحة

**هذا يعني**: المستخدم **دائماً** سيرى شيئاً مفيداً!

---

## 👤 ملكية الوسوم (Tag Ownership)

وسوم عامة أو خاصة بمستخدم معين أو فريق.

```php
// إنشاء وسم خاص بمستخدم
$tag = Tag::create([
    'name' => ['ar' => 'المفضلة'],
    'owner_type' => User::class,
    'owner_id' => $user->id,
]);

// الاستعلام
Tag::global()->get();               // وسوم بدون مالك
Tag::ownedBy($user)->get();         // وسوم هذا المستخدم
Tag::availableTo($user)->get();     // عامة + وسوم هذا المستخدم
```

---

## 🏷️ أنواع الوسوم والـ Enums

### استخدام النصوص

```php
$post->attachTag('PHP', 'language');
Tag::ofType('language')->get();
```

### استخدام BackedEnum (الأفضل)

```php
enum TagType: string
{
    case Category = 'category';
    case Label = 'label';
    case Language = 'language';
    case Framework = 'framework';
}

// إضافة وسوم بأنواع محددة
$tag = Tag::findOrCreate('PHP', TagType::Language, 'ar');
$post->attachTag('لاراڤل', TagType::Framework);

// المزامنة حسب النوع
$post->syncTagsWithType(['PHP', 'Go'], TagType::Language);

// الاستعلام حسب النوع
Tag::ofType(TagType::Language)->get();
Post::withTagsOfType(TagType::Framework)->get();
```

---

## 🔍 اقتراحات البحث (Autocomplete)

بناء حقول بحث وسوم مع اقتراحات أثناء الكتابة:

```php
// بحث بسيط
$suggestions = Tag::suggestions('تقنية');

// مع تحديد النوع واللغة والحد
$suggestions = Tag::suggestions('PHP', TagType::Language, 'ar', 5);

// النتيجة: ['PHP', 'PHPUnit', ...]
```

---

## ⚡ التخزين المؤقت (Caching)

للتطبيقات عالية الحركة، فعّل التخزين المؤقت المدمج. **المهم**: التخزين المؤقت يُبطل تلقائياً عند أي تغيير!

### الإعدادات

```php
// config/laravel-turbo-tags.php
'cache' => [
    'enabled' => true,
    'ttl' => 3600,              // ثانية
    'store' => null,             // null = متجر التخزين المؤقت الافتراضي
    'key_prefix' => 'turbo_tags',
],
```

### الاستخدام

```php
// جميع الوسوم من التخزين المؤقت
$tags = Tag::allCached();

// وسوم نوع معين
$tags = Tag::allOfTypeCached(TagType::Language);

// عمليات الدفعات (1 إبطال واحد بدل N)
$tags = Tag::findOrCreateMany(['PHP', 'لاراڤل', 'Go']);

// إبطال يدوي إذا احتجت
Tag::flushTagCache();
```

---

## 📦 البحث والإنشاء (findOrCreate)

إيجاد الوسوم أو إنشاؤها بكفاءة — مع التخزين المؤقت ودعم الدفعات:

```php
// وسم واحد
$tag = Tag::findOrCreate('لاراڤل', TagType::Framework, 'ar');

// دفعة كاملة (استعلام واحد للبحث + إنشاء الناقص، إبطال واحد)
$tags = Tag::findOrCreateMany(
    ['PHP', 'لاراڤل', 'اختبار'],
    TagType::Language,
    'ar'
);
```

---

## ⚙️ الإعدادات الكاملة

بعد نشر الملفات (`php artisan vendor:publish --tag="laravel-turbo-tags-config"`):

```php
return [
    // موديل الوسم المخصص (وسّع Tag إذا احتجت سلوك مخصص)
    'tag_model' => \LaraArabDev\TurboTags\Models\Tag::class,

    // أسماء الجداول
    'tables' => [
        'tags' => 'tags',
        'taggables' => 'taggables',
    ],

    // إعدادات اللغة
    'locale' => [
        'primary' => 'ar',      // اللغة الأساسية (مثلاً: 'ar' للعربية)
        'fallback' => 'en',     // لغة البديل
    ],

    // توليد الروابط النصية
    'slugger' => [
        'source' => 'name',
        'generate_on_create' => true,   // توليد تلقائي عند الإنشاء
        'generate_unique' => true,      // -2, -3 إلخ للتفرد
    ],

    // التخزين المؤقت
    'cache' => [
        'enabled' => false,             // فعّل في الإنتاج
        'ttl' => 3600,
        'store' => null,
        'key_prefix' => 'turbo_tags',
    ],

    // تحسين الأداء
    'performance' => [
        'chunk_size' => 1000,
    ],

    // اقتراحات البحث
    'suggestions' => [
        'limit' => 10,                  // عدد الاقتراحات
        'min_length' => 2,              // الحد الأدنى لطول البحث
    ],
];
```

---

## 🧪 الاختبارات والجودة

الحزمة تحتوي على **101 اختبار** بـ **95%+ تغطية** وتمر **PHPStan بالمستوى الأقصى**:

```bash
# تشغيل الاختبارات
composer test

# الاختبارات مع تقرير التغطية
composer test-coverage

# التحليل الثابت
composer analyse

# فحص نمط الكود
composer format-test

# إصلاح نمط الكود
composer format
```

---

## 📋 مرجع API الكامل

### دوال Trait `HasTags`

| الدالة | الوصف |
| --- | --- |
| `attachTag($tags, $type, $locale)` | إضافة وسم أو عدة وسوم |
| `detachTag($tags, $type, $locale)` | حذف وسم أو عدة وسوم |
| `syncTags($tags, $type, $locale)` | استبدال جميع الوسوم |
| `syncTagsWithType($tags, $type, $locale)` | مزامنة وسوم نوع معين |
| `removeAllTags()` | حذف جميع الوسوم من الموديل |
| `hasTag($tag, $type, $locale)` | التحقق من وجود وسم |
| `hasAllTags($tags, $type, $locale)` | التحقق من وجود جميع الوسوم |
| `hasAnyTags($tags, $type, $locale)` | التحقق من وجود أي من الوسوم |

### دوال موديل `Tag`

| الدالة | الوصف |
| --- | --- |
| `Tag::findOrCreate($name, $type, $locale)` | البحث أو الإنشاء |
| `Tag::findOrCreateMany($names, $type, $locale)` | دفعة من البحث/الإنشاء |
| `Tag::allCached()` | جميع الوسوم (من التخزين المؤقت) |
| `Tag::allOfTypeCached($type)` | وسوم نوع محدد (من التخزين المؤقت) |
| `Tag::suggestions($search, $type, $locale, $limit)` | البحث عن اقتراحات |
| `Tag::roots()` | وسوم جذرية فقط |
| `Tag::global()` | وسوم بدون مالك |
| `Tag::ownedBy($model)` | وسوم هذا الموديل |
| `Tag::ofType($type)` | وسوم نوع محدد |
| `Tag::withSlug($slug)` | البحث بالرابط النصي |

### دوال نسخة `Tag`

| الدالة | الوصف |
| --- | --- |
| `$tag->parent` | الوسم الوالد |
| `$tag->children` | الأطفال المباشرون |
| `$tag->ancestors()` | جميع الأسلاف (من الأسفل لأعلى) |
| `$tag->descendants()` | جميع الأحفاد (مسطح) |
| `$tag->isRoot()` | بدون والد؟ |
| `$tag->isLeaf()` | بدون أطفال؟ |
| `$tag->getTranslatedName($locale)` | الحصول على الاسم المترجم |
| `$tag->setTranslatedName($value, $locale)` | تعيين ترجمة |
| `$tag->getTranslations()` | جميع الترجمات |

---

## 🤝 المساهمة

نرحب بالمساهمات من المجتمع! يرجى مراجعة [دليل المساهمة](.github/CONTRIBUTING.md).

### معايير الكود

- اتبع [Conventional Commits](https://www.conventionalcommits.org/)
- كل رسالة commits يجب أن تتبع: `type(scope): description`
- أنواع مسموحة: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`
- الفروع: `type/short-description` (مثل `feat/add-caching`)

---

## 🔒 الأمان

يرجى مراجعة [سياسة الأمان](SECURITY.md) لكيفية الإبلاغ عن ثغرات الأمان. **لا تفتح issue عامة**.

---

## 📄 الترخيص

الرخصة: MIT. يرجى مراجعة [ملف الترخيص](LICENSE.md).

---

<p align="center">
    <sub>صُنع بـ ❤️ بواسطة <a href="https://github.com/LaraArabDev">LaraArabDev</a></sub><br>
    <sub>مجتمع عربي مفتوح المصدر متخصص في حزم Laravel احترافية</sub>
</p>

</div>
