# Governance | الحوكمة

<details open>
<summary><strong>English</strong></summary>

## Project Governance

**:package_name** is maintained by the [LaraArabDev](https://github.com/LaraArabDev) community.

### Roles

#### Maintainers

Maintainers have full access to the repository and are responsible for:

- Reviewing and merging pull requests
- Triaging issues and feature requests
- Releasing new versions
- Enforcing the Code of Conduct
- Setting the project's technical direction

#### Contributors

Anyone who submits a pull request, opens an issue, or participates in discussions is a contributor. Contributors are expected to follow the [Code of Conduct](CODE_OF_CONDUCT.md) and [Contributing Guide](CONTRIBUTING.md).

#### Reviewers

Trusted contributors who regularly provide high-quality reviews may be granted reviewer status. Reviewers can approve PRs but cannot merge without maintainer approval.

### Decision Making

- **Minor changes** (bug fixes, docs, small improvements): A single maintainer approval is sufficient
- **Major changes** (new features, breaking changes, architectural decisions): Require discussion in an issue/PR and at least two maintainer approvals
- **Breaking changes**: Must be documented in the CHANGELOG and follow semantic versioning

### Release Process

1. All CI checks must pass (tests, static analysis, code style, security)
2. CHANGELOG must be updated
3. Releases follow [Semantic Versioning](https://semver.org/)
4. Releases are automated via the release workflow when commits follow [Conventional Commits](https://www.conventionalcommits.org/)

### Conflict Resolution

1. Technical disagreements should be discussed in the relevant issue or PR
2. If consensus cannot be reached, maintainers make the final decision
3. Code of Conduct violations are handled by maintainers as described in the [Code of Conduct](CODE_OF_CONDUCT.md)

</details>

---

<details>
<summary><strong>العربية</strong></summary>

<div dir="rtl">

## حوكمة المشروع

**:package_name** يُدار بواسطة مجتمع [LaraArabDev](https://github.com/LaraArabDev).

### الأدوار

#### المشرفون (Maintainers)

المشرفون لديهم صلاحية كاملة على المستودع وهم مسؤولون عن:

- مراجعة ودمج طلبات السحب (Pull Requests)
- فرز المشاكل وطلبات الميزات
- إصدار النسخ الجديدة
- تطبيق ميثاق قواعد السلوك
- تحديد التوجه التقني للمشروع

#### المساهمون (Contributors)

أي شخص يقدم طلب سحب أو يفتح Issue أو يشارك في المناقشات يُعتبر مساهماً. يُتوقع من المساهمين اتباع [ميثاق قواعد السلوك](CODE_OF_CONDUCT.md) و[دليل المساهمة](CONTRIBUTING.md).

#### المراجعون (Reviewers)

المساهمون الموثوقون الذين يقدمون مراجعات عالية الجودة بانتظام قد يُمنحون صلاحية المراجعة. يمكن للمراجعين الموافقة على PRs لكن لا يمكنهم الدمج دون موافقة المشرف.

### اتخاذ القرارات

- **التغييرات البسيطة** (إصلاح الأخطاء، التوثيق، التحسينات الصغيرة): موافقة مشرف واحد كافية
- **التغييرات الكبيرة** (ميزات جديدة، تغييرات جذرية، قرارات معمارية): تتطلب نقاشاً في Issue/PR وموافقة مشرفَين على الأقل
- **التغييرات الجذرية (Breaking Changes)**: يجب توثيقها في CHANGELOG واتباع الإصدار الدلالي (Semantic Versioning)

### عملية الإصدار

1. يجب أن تنجح جميع فحوصات CI (الاختبارات، التحليل الثابت، نمط الكود، الأمان)
2. يجب تحديث CHANGELOG
3. تتبع الإصدارات [الإصدار الدلالي](https://semver.org/)
4. الإصدارات مؤتمتة عبر سير العمل (workflow) عندما تتبع الـ commits صيغة [Conventional Commits](https://www.conventionalcommits.org/)

### حل النزاعات

1. يجب مناقشة الخلافات التقنية في الـ Issue أو PR المعني
2. إذا لم يتم التوصل إلى توافق، يتخذ المشرفون القرار النهائي
3. تُعالج انتهاكات ميثاق قواعد السلوك من قبل المشرفين كما هو موضح في [ميثاق قواعد السلوك](CODE_OF_CONDUCT.md)

</div>

</details>
