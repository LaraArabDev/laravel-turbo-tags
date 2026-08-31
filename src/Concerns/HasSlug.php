<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags\Concerns;

use Illuminate\Support\Str;
use LaraArabDev\TurboTags\Models\Tag;

/**
 * Provides automatic slug generation for tag models.
 *
 * Generates URL-friendly slugs from the primary locale name on creation.
 * Enforces uniqueness by appending numeric suffixes when needed.
 */
trait HasSlug
{
    /**
     * Boot the trait: register a creating event to auto-generate slugs.
     */
    public static function bootHasSlug(): void
    {
        static::creating(function (Tag $model): void {
            if (! config('laravel-turbo-tags.slugger.generate_on_create', true)) {
                return;
            }

            if (! empty($model->slug)) {
                return;
            }

            $model->slug = static::generateUniqueSlug($model->generateSlugSource());
        });
    }

    /**
     * Derive the slug source string from the primary locale translation.
     *
     * Falls back to the first available translation if the primary locale is missing.
     */
    protected function generateSlugSource(): string
    {
        $primary = config('laravel-turbo-tags.locale.primary');
        $primary = is_string($primary) ? $primary : app()->getLocale();

        $translations = $this->getTranslations();

        return $translations[$primary] ?? (reset($translations) ?: '');
    }

    /**
     * Generate a unique slug from the given source string.
     *
     * Appends `-2`, `-3`, etc. suffixes to ensure uniqueness when
     * `generate_unique` is enabled in config.
     *
     * @param  string  $source  The source string to slugify.
     */
    public static function generateUniqueSlug(string $source): string
    {
        $slug = Str::slug($source);

        if ($slug === '') {
            $slug = Str::random(8);
        }

        if (! config('laravel-turbo-tags.slugger.generate_unique', true)) {
            return $slug;
        }

        $originalSlug = $slug;
        $counter = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
