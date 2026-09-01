# Laravel Turbo Tags

A high-performance tagging system for Laravel applications with support for multiple languages including Arabic.

## Features

- ⚡ High-performance tagging engine
- 🌍 Multi-language support (including Arabic - العربية)
- 📦 Easy integration with Eloquent models
- 🔍 Advanced filtering and search capabilities
- 💾 Database-backed tag storage

## Arabic Support - دعم اللغة العربية

This package now includes full support for Arabic language tagging:
- Arabic tag creation and management
- Right-to-left (RTL) text handling
- Arabic search and filtering
- Unicode normalization for Arabic characters

## Installation

```bash
composer require laradev/turbo-tags
```

## Usage

```php
use LaraArabDev\TurboTags\Models\Tag;

// Create a tag in English
$tag = Tag::create(['name' => 'Laravel']);

// Create a tag in Arabic
$arabicTag = Tag::create(['name' => 'لاراقل']);

// Attach tags to a model
$post->tags()->attach($tag);
```

## License

MIT
