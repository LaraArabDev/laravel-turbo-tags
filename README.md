<p align="center">
    <img src="art/banner.svg" alt=":package_name Banner" style="width: 100%; max-width: 800px;">
</p>

<h1 align="center">:package_name</h1>

<p align="center">
    <strong>:package_description</strong>
</p>

<p align="center">
    <a href="https://packagist.org/packages/laraarabdev/:package_slug"><img src="https://img.shields.io/packagist/v/laraarabdev/:package_slug.svg?style=flat-square" alt="Latest Version"></a>
    <a href="https://packagist.org/packages/laraarabdev/:package_slug"><img src="https://img.shields.io/packagist/dt/laraarabdev/:package_slug.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laraarabdev/:package_slug"><img src="https://img.shields.io/packagist/l/laraarabdev/:package_slug.svg?style=flat-square" alt="License"></a>
    <a href="https://packagist.org/packages/laraarabdev/:package_slug"><img src="https://img.shields.io/packagist/php-v/laraarabdev/:package_slug.svg?style=flat-square" alt="PHP"></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/laravel-11.x%20%7C%2012.x-red?style=flat-square" alt="Laravel"></a>
</p>

<p align="center">
    <a href="https://github.com/LaraArabDev/:package_repo/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/:package_repo/tests.yml?branch=main&label=tests&style=flat-square" alt="Tests"></a>
    <a href="https://codecov.io/gh/LaraArabDev/:package_repo"><img src="https://img.shields.io/codecov/c/github/LaraArabDev/:package_repo?style=flat-square" alt="codecov"></a>
    <a href="https://github.com/LaraArabDev/:package_repo/actions/workflows/static-analysis.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/:package_repo/static-analysis.yml?branch=main&label=phpstan&style=flat-square" alt="Static Analysis"></a>
    <a href="https://github.com/LaraArabDev/:package_repo/actions/workflows/security.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/:package_repo/security.yml?branch=main&label=security&style=flat-square" alt="Security Audit"></a>
    <a href="https://github.com/LaraArabDev/:package_repo/actions/workflows/mutation-testing.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/:package_repo/mutation-testing.yml?branch=main&label=infection&style=flat-square" alt="Mutation Testing"></a>
    <a href="https://github.com/LaraArabDev/:package_repo/actions/workflows/code-style.yml"><img src="https://img.shields.io/github/actions/workflow/status/LaraArabDev/:package_repo/code-style.yml?branch=main&label=pint&style=flat-square" alt="Code Style"></a>
</p>

<p align="center">
    :package_description<br>
    PHP 8.2 – 8.4 · Laravel 11 / 12
</p>

<p align="center">
    <b><a href="https://github.com/LaraArabDev">LaraArabDev</a></b> — We build, develop, empower, and contribute. An Arab open-source community crafting production-grade Laravel packages.<br>
    <b><a href="https://github.com/LaraArabDev">LaraArabDev</a></b> — نبني، نطوّر، نُمكّن، ونُساهم. مجتمع عربي مفتوح المصدر يصنع حزم Laravel احترافية وجاهزة للإنتاج.
</p>

<p align="center">
    <a href="#-quick-start">Quick Start</a> ·
    <a href="#-features">Features</a> ·
    <a href="#-configuration">Configuration</a> ·
    <a href="#-testing">Testing</a> ·
    <a href="#-changelog">Changelog</a>
</p>

---

## What is :package_name?

<!--
TODO: Write a compelling description of what your package does and why someone should use it.
-->

**:package_name** provides ... for your Laravel application.

---

## 🚀 Quick Start

```bash
composer require laraarabdev/:package_slug
php artisan vendor:publish --tag=":package_slug-config"
```

| Requirement | Version |
| --- | --- |
| PHP | 8.2, 8.3, or 8.4 |
| Laravel | 11 or 12 |

---

## 📦 Features

<!--
TODO: Document your package features here.
-->

- Feature one
- Feature two
- Feature three

---

## ⚙️ Configuration

After publishing the config file, you can customize the behavior:

```php
// config/:package_slug.php

return [
    // ...
];
```

---

## 🧪 Testing

```bash
composer test
```

Run the full test suite with code coverage:

```bash
composer test-coverage
```

Run static analysis:

```bash
composer analyse
```

Run code style checks:

```bash
composer format-test
```

Fix code style:

```bash
composer format
```

---

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## 🤝 Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Commit Convention

This project follows [Conventional Commits](https://www.conventionalcommits.org/). All commit messages and PR titles must follow this format:

```
type(scope): description
```

**Allowed types:** `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, `revert`, `hotfix`

**Branch naming:** `type/short-description` (e.g., `feat/add-caching`, `fix/slug-generation`)

### Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

<p align="center">
    <sub>Built with ❤️ by <a href="https://github.com/LaraArabDev">LaraArabDev</a></sub>
</p>
