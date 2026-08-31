# Contributing to TurboTags | المساهمة في TurboTags

<details open>
<summary><strong>English</strong></summary>

Thank you for considering contributing to **TurboTags**! We welcome contributions from the community.

Please read our [Code of Conduct](CODE_OF_CONDUCT.md) before participating.

## Development Setup

1. Fork the repository
2. Clone your fork: `git clone git@github.com:your-username/LaravelTurboTags.git`
3. Install dependencies: `composer install`
4. Create a feature branch: `git checkout -b feat/your-feature`
5. Run the test suite to ensure everything works: `composer test`

## Branch Naming

All branches must follow this pattern:

```
type/short-description
```

**Valid types:** `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, `revert`, `hotfix`, `release`

**Examples:**
- `feat/add-caching`
- `fix/null-pointer`
- `docs/update-readme`

## Commit Messages

This project follows [Conventional Commits](https://www.conventionalcommits.org/):

```
type(scope): short description

Optional body with more details.
```

**Examples:**
- `feat: add tag caching support`
- `fix(model): resolve duplicate slug generation`
- `docs: update installation instructions`
- `test: add coverage for edge cases`

## PR Title

PR titles **must** follow the same conventional commit format as commit messages. This is enforced by CI.

## Acceptance Criteria

Every PR must meet **all** of the following before it can be merged:

### Required (all PRs)

- [ ] **All CI checks pass** — tests, PHPStan, Pint, security audit
- [ ] **Branch name** follows `type/short-description` convention
- [ ] **PR title** follows conventional commit format (`type: description`)
- [ ] **All commits** follow conventional commit format
- [ ] **No merge conflicts** with `main`
- [ ] **Reviewer checklist** is addressed (auto-posted on every PR)

### Code Changes

- [ ] **Tests included** — new/changed behavior has corresponding test coverage
- [ ] **No decrease in coverage** — coverage must stay the same or improve
- [ ] **PHPStan passes** at max level with no baseline additions
- [ ] **Code style** passes Laravel Pint (PSR-12)
- [ ] **No N+1 queries** introduced
- [ ] **No hardcoded values** that should be configurable
- [ ] **No sensitive data** exposed (keys, tokens, credentials)

### New Features

- [ ] **Feature is configurable** — can be enabled/disabled or customized via config
- [ ] **README updated** with usage examples
- [ ] **CHANGELOG updated** under `[Unreleased]`
- [ ] **Migration included** if schema changes are needed

### Bug Fixes

- [ ] **Regression test** included that reproduces the bug
- [ ] **Root cause** is addressed, not just symptoms
- [ ] **CHANGELOG updated** under `[Unreleased]`

### Breaking Changes

- [ ] **Justified and necessary** — no non-breaking alternative exists
- [ ] **Migration path documented** for existing users
- [ ] **CHANGELOG** clearly marks the breaking change
- [ ] **Commit/PR** includes `BREAKING CHANGE:` footer or `!` in type
- [ ] **Major version bump** will be triggered

### Documentation

- [ ] **Public API** changes are documented in README
- [ ] **Inline comments** explain non-obvious logic
- [ ] **PHPDoc blocks** on public methods

## Quality Checks

Before submitting a PR, ensure all checks pass locally:

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse

# Check code style
composer format-test

# Fix code style
composer format
```

## Pull Request Process

1. Ensure your branch is up to date with `main`
2. Verify all acceptance criteria are met
3. All CI checks must pass (14 workflows)
4. Address the auto-generated reviewer checklist
5. At least one maintainer must approve
6. PRs are squash-merged — the PR title becomes the commit message

## Review Process

- PRs are reviewed within **48 hours** on business days
- Maintainers may request changes — please address them promptly
- Stale PRs (no activity for 30 days) are automatically closed
- Trivial fixes (typos, formatting) may be merged by a single maintainer
- Significant changes require at least two maintainer approvals

## Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) with PSR-12. Run `composer format` to auto-fix.

## Reporting Bugs

Use the [bug report template](https://github.com/LaraArabDev/LaravelTurboTags/issues/new?template=bug_report.yml).

## Suggesting Features

Use the [feature request template](https://github.com/LaraArabDev/LaravelTurboTags/issues/new?template=feature_request.yml).

## Security

See our [Security Policy](../SECURITY.md) for reporting vulnerabilities.

</details>

---

<details>
<summary><strong>العربية</strong></summary>

<div dir="rtl">

شكراً لتفكيرك في المساهمة في **TurboTags**! نرحب بالمساهمات من المجتمع.

يرجى قراءة [ميثاق قواعد السلوك](CODE_OF_CONDUCT.md) قبل المشاركة.

## إعداد بيئة التطوير

1. انسخ المستودع (Fork)
2. استنسخ نسختك: `git clone git@github.com:your-username/LaravelTurboTags.git`
3. ثبّت المتطلبات: `composer install`
4. أنشئ فرعاً جديداً: `git checkout -b feat/your-feature`
5. شغّل الاختبارات للتأكد من أن كل شيء يعمل: `composer test`

## تسمية الفروع

يجب أن تتبع جميع الفروع هذا النمط:

```
type/short-description
```

**الأنواع المسموحة:** `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, `revert`, `hotfix`, `release`

**أمثلة:**
- `feat/add-caching`
- `fix/null-pointer`
- `docs/update-readme`

## رسائل الـ Commit

يتبع هذا المشروع معيار [Conventional Commits](https://www.conventionalcommits.org/):

```
type(scope): وصف قصير

نص اختياري مع مزيد من التفاصيل.
```

**أمثلة:**
- `feat: add tag caching support`
- `fix(model): resolve duplicate slug generation`
- `docs: update installation instructions`
- `test: add coverage for edge cases`

## عنوان طلب السحب (PR Title)

يجب أن يتبع عنوان PR نفس صيغة Conventional Commits. يتم فرض هذا تلقائياً بواسطة CI.

## معايير القبول

يجب أن يستوفي كل PR **جميع** المعايير التالية قبل دمجه:

### المطلوب (جميع PRs)

- [ ] **نجاح جميع فحوصات CI** — الاختبارات، PHPStan، Pint، تدقيق الأمان
- [ ] **اسم الفرع** يتبع صيغة `type/short-description`
- [ ] **عنوان PR** يتبع صيغة Conventional Commits (`type: description`)
- [ ] **جميع الـ commits** تتبع صيغة Conventional Commits
- [ ] **لا توجد تعارضات دمج** مع `main`
- [ ] **قائمة المراجعة** تمت معالجتها (تُنشر تلقائياً على كل PR)

### تغييرات الكود

- [ ] **الاختبارات مضمّنة** — السلوك الجديد/المعدّل له تغطية اختبار مناسبة
- [ ] **لا انخفاض في التغطية** — يجب أن تبقى التغطية كما هي أو تتحسن
- [ ] **PHPStan ينجح** عند أعلى مستوى بدون إضافات baseline
- [ ] **نمط الكود** يجتاز Laravel Pint (PSR-12)
- [ ] **لا استعلامات N+1** جديدة
- [ ] **لا قيم ثابتة** يجب أن تكون قابلة للتكوين
- [ ] **لا بيانات حساسة** مكشوفة (مفاتيح، رموز، بيانات اعتماد)

### الميزات الجديدة

- [ ] **الميزة قابلة للتكوين** — يمكن تفعيلها/تعطيلها أو تخصيصها عبر config
- [ ] **تحديث README** بأمثلة الاستخدام
- [ ] **تحديث CHANGELOG** تحت `[Unreleased]`
- [ ] **تضمين migration** إذا كانت هناك تغييرات في المخطط

### إصلاح الأخطاء

- [ ] **اختبار انحدار** مضمّن يعيد إنتاج الخطأ
- [ ] **معالجة السبب الجذري** وليس الأعراض فقط
- [ ] **تحديث CHANGELOG** تحت `[Unreleased]`

### التغييرات الجذرية (Breaking Changes)

- [ ] **مبررة وضرورية** — لا يوجد بديل غير جذري
- [ ] **مسار الترحيل موثّق** للمستخدمين الحاليين
- [ ] **CHANGELOG** يحدد التغيير الجذري بوضوح
- [ ] **الـ Commit/PR** يتضمن `BREAKING CHANGE:` أو `!` في النوع
- [ ] **سيتم تفعيل رفع النسخة الرئيسية** (Major version bump)

### التوثيق

- [ ] **واجهة API العامة** موثقة في README
- [ ] **التعليقات المضمّنة** تشرح المنطق غير الواضح
- [ ] **كتل PHPDoc** على الدوال العامة

## فحوصات الجودة

قبل تقديم PR، تأكد من نجاح جميع الفحوصات محلياً:

```bash
# تشغيل الاختبارات
composer test

# تشغيل الاختبارات مع التغطية
composer test-coverage

# تشغيل التحليل الثابت
composer analyse

# فحص نمط الكود
composer format-test

# إصلاح نمط الكود
composer format
```

## عملية طلب السحب

1. تأكد من أن فرعك محدّث مع `main`
2. تحقق من استيفاء جميع معايير القبول
3. يجب أن تنجح جميع فحوصات CI (14 سير عمل)
4. عالج قائمة المراجعة المولّدة تلقائياً
5. يجب أن يوافق مشرف واحد على الأقل
6. يتم دمج PRs بطريقة squash-merge — عنوان PR يصبح رسالة الـ commit

## عملية المراجعة

- تتم مراجعة PRs خلال **48 ساعة** في أيام العمل
- قد يطلب المشرفون تعديلات — يرجى معالجتها بسرعة
- PRs غير النشطة (بدون نشاط لمدة 30 يوماً) تُغلق تلقائياً
- الإصلاحات البسيطة (أخطاء إملائية، تنسيق) يمكن دمجها بموافقة مشرف واحد
- التغييرات الكبيرة تتطلب موافقة مشرفَين على الأقل

## نمط الكود

يستخدم هذا المشروع [Laravel Pint](https://laravel.com/docs/pint) مع PSR-12. شغّل `composer format` للإصلاح التلقائي.

## الإبلاغ عن الأخطاء

استخدم [نموذج الإبلاغ عن الأخطاء](https://github.com/LaraArabDev/LaravelTurboTags/issues/new?template=bug_report.yml).

## اقتراح الميزات

استخدم [نموذج طلب الميزات](https://github.com/LaraArabDev/LaravelTurboTags/issues/new?template=feature_request.yml).

## الأمان

راجع [سياسة الأمان](../SECURITY.md) للإبلاغ عن الثغرات الأمنية.

</div>

</details>
