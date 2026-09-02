# Laravel Turbo Tags

## مرحبا بك في Laravel Turbo Tags 🚀

**Laravel Turbo Tags** هي مكتبة قوية وفعالة لإدارة الوسوم والتصنيفات في تطبيقات Laravel. توفر الأداة أداءً عالياً وسهولة في الاستخدام مع دعم كامل للغة العربية.

---

## 📋 جدول المحتويات

1. [المزايا](#المزايا)
2. [التثبيت](#التثبيت)
3. [أمثلة الاستخدام](#أمثلة-الاستخدام)
4. [التوثيق](#التوثيق)
5. [إرشادات المساهمة](#إرشادات-المساهمة)
6. [الترخيص](#الترخيص)

---

## 🌟 المزايا

- ✅ **أداء عالي**: معالجة سريعة وفعالة للوسوم
- ✅ **سهولة الاستخدام**: واجهة برمجية بديهية وواضحة
- ✅ **دعم العربية الكامل**: تدعم اللغة العربية بشكل أصلي
- ✅ **المرونة**: تخصيص الوسوم حسب احتياجاتك
- ✅ **التوثيق الشامل**: توثيق كامل بالعربية
- ✅ **الاختبارات الشاملة**: اختبارات شاملة تغطي جميع الحالات

---

## 📦 التثبيت

### المتطلبات
- PHP >= 7.4
- Laravel >= 7.0
- Composer

### خطوات التثبيت

**الخطوة 1**: تثبيت الحزمة عبر Composer
```bash
composer require laraarabdev/laravel-turbo-tags
```

**الخطوة 2**: نشر الملفات (اختياري)
```bash
php artisan vendor:publish --provider="LaraArabDev\LaravelTurboTags\ServiceProvider"
```

**الخطوة 3**: تشغيل المهام المطلوبة
```bash
php artisan migrate
```

**الخطوة 4**: إضافة Trait إلى نماذجك
```php
use LaraArabDev\LaravelTurboTags\Traits\Taggable;

class Post extends Model
{
    use Taggable;
}
```

---

## 💡 أمثلة الاستخدام

### مثال 1: إضافة وسوم إلى نموذج

```php
$post = Post::find(1);

// إضافة وسم واحد
$post->addTag('Laravel');

// إضافة عدة وسوم
$post->addTags(['PHP', 'ويب', 'تطوير']);

// إضافة وسم مع تصنيف
$post->addTag('سريع', 'الأداء');
```

### مثال 2: الحصول على الوسوم

```php
$post = Post::find(1);

// الحصول على جميع الوسوم
$tags = $post->tags;

// الحصول على الوسوم بتصنيف معين
$performanceTags = $post->tagsByCategory('الأداء');

// البحث عن الوسوم
$searchResults = $post->searchTags('سريع');
```

### مثال 3: حذف الوسوم

```php
$post = Post::find(1);

// حذف وسم واحد
$post->removeTag('Laravel');

// حذف عدة وسوم
$post->removeTags(['PHP', 'ويب']);

// حذف جميع الوسوم
$post->clearTags();
```

### مثال 4: البحث عن نماذج بواسطة الوسوم

```php
// البحث عن المنشورات بوسم معين
$posts = Post::withTag('Laravel')->get();

// البحث عن المنشورات بعدة وسوم
$posts = Post::withTags(['PHP', 'ويب'])->get();

// البحث عن المنشورات بأي من الوسوم
$posts = Post::withAnyTag(['Laravel', 'ويب'])->get();
```

### مثال 5: الإحصائيات والتقارير

```php
$post = Post::find(1);

// عدد الوسوم
$count = $post->tagCount();

// أكثر الوسوم استخداماً
$popularTags = Post::mostPopularTags(10);

// الوسوم الحديثة
$recentTags = Post::recentTags(5);
```

---

## 📚 التوثيق

### واجهة برمجية (API)

#### الدوال الأساسية

| الدالة | الوصف | الاستخدام |
|--------|-------|----------|
| `addTag($tag)` | إضافة وسم واحد | `$model->addTag('وسم')` |
| `addTags($tags)` | إضافة عدة وسوم | `$model->addTags(['وسم1', 'وسم2'])` |
| `removeTag($tag)` | حذف وسم | `$model->removeTag('وسم')` |
| `removeTags($tags)` | حذف عدة وسوم | `$model->removeTags(['وسم1', 'وسم2'])` |
| `clearTags()` | حذف جميع الوسوم | `$model->clearTags()` |
| `getTags()` | الحصول على الوسوم | `$model->getTags()` |
| `tagCount()` | عد الوسوم | `$model->tagCount()` |

#### الاستعلامات

| الاستعلام | الوصف |
|----------|-------|
| `withTag($tag)` | البحث بوسم واحد |
| `withTags($tags)` | البحث بعدة وسوم |
| `withAnyTag($tags)` | البحث بأي من الوسوم |
| `withoutTag($tag)` | استبعاد وسم معين |

#### الخيارات المتقدمة

```php
// مع العلاقات المحملة مسبقاً
$posts = Post::with('tags')->get();

// مع التصفية
$posts = Post::withTag('Laravel')->where('published', true)->get();

// مع الترتيب
$posts = Post::withTag('Laravel')->orderBy('created_at', 'desc')->get();
```

---

## 🤝 إرشادات المساهمة

نرحب بمساهماتك! إذا كنت تريد المساهمة في المشروع، يرجى اتباع الخطوات التالية:

### 1. الإعداد الأولي

**الخطوة 1**: انسخ المشروع (Fork)
```bash
اضغط على زر Fork في الجزء العلوي من الصفحة
```

**الخطوة 2**: استنسخ المشروع المنسوخ لديك
```bash
git clone https://github.com/YOUR_USERNAME/laravel-turbo-tags.git
cd laravel-turbo-tags
```

**الخطوة 3**: أضف المستودع الأصلي كـ upstream
```bash
git remote add upstream https://github.com/LaraArabDev/laravel-turbo-tags.git
```

### 2. إنشاء فرع للميزة الجديدة

```bash
# تحديث الفرع الرئيسي
git fetch upstream
git checkout main
git merge upstream/main

# إنشاء فرع جديد
git checkout -b feat/اسم-الميزة
```

### 3. إجراء التغييرات

- قم بإجراء التغييرات المطلوبة على الكود
- اكتب اختبارات شاملة للميزة الجديدة
- تأكد من اتباع معايير الكود (PSR-12)

### 4. الاختبار

```bash
# تشغيل الاختبارات
./vendor/bin/phpunit

# فحص جودة الكود
./vendor/bin/phpstan analyse src/
```

### 5. الالتزام والدفع

```bash
# إضافة التغييرات
git add .

# الالتزام برسالة واضحة
git commit -m "feat: وصف الميزة الجديدة"

# دفع التغييرات
git push origin feat/اسم-الميزة
```

### 6. إنشاء طلب سحب (Pull Request)

- اذهب إلى مستودع GitHub الأصلي
- اضغط على زر "New Pull Request"
- حدد الفرع الجديد الخاص بك
- أضف وصفاً شاملاً للتغييرات
- انتظر المراجعة والتعليقات

### معايير المساهمة

✅ **يجب عليك:**
- اتباع معايير PSR-12 للكود
- كتابة اختبارات شاملة
- توثيق الكود بشكل واضح
- استخدام رسائل التزام واضحة وموجزة

❌ **تجنب:**
- الأخطاء الإملائية والنحوية
- الكود غير المختبر
- التعليقات غير الضرورية
- المتغيرات ذات الأسماء الغامضة

### نصائح مفيدة

- اقرأ الوثائق الموجودة قبل البدء
- تواصل مع الفريق عند الشك
- اطلب المساعدة عند الحاجة
- كن مهذباً واحترم آراء الآخرين

---

## 📄 الترخيص

هذا المشروع مرخص تحت رخصة MIT. راجع ملف [LICENSE](LICENSE) للمزيد من التفاصيل.

---

## 📞 التواصل والدعم

- **البريد الإلكتروني**: support@laraarabdev.com
- **الموقع**: https://laraarabdev.com
- **مشاكل وأسئلة**: https://github.com/LaraArabDev/laravel-turbo-tags/issues

---

## 🙏 شكر خاص

شكراً لكل من ساهم في هذا المشروع. نحن نقدر مساهماتك ودعمك المستمر.

**Made with ❤️ by LaraArabDev Community**
