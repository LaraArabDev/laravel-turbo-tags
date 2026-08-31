# Changelog

All notable changes to `laravel-turbo-tags` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Polymorphic tagging system via `HasTags` trait
- `Tag` model with JSON-based translatable names
- Automatic slug generation with unique enforcement (`HasSlug` trait)
- Translation support with locale fallback chain (`HasTranslatableName` trait)
- `findOrCreate` and `findOrCreateMany` for convenient tag creation
- Tag types for categorizing tags (e.g., `language`, `framework`)
- Query scopes: `withAllTags`, `withAnyTags`, `withoutTags`, `withTagsOfType`
- Tag sync operations: `syncTags`, `syncTagsWithType`
- Tag suggestions with configurable search
- Metadata support via JSON column
- Tag ordering via `order_column`
- Eager loading helpers: `withTagsLoaded`, `withTagCount`
- Comprehensive configuration for locales, slugs, cache, and performance
- Full test suite with Pest
