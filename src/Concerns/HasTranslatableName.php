<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags\Concerns;

/**
 * Provides translatable name support for Eloquent models.
 *
 * Stores translations as a JSON object keyed by locale.
 * Resolves translations through a fallback chain:
 * requested locale -> config primary -> app locale -> config fallback -> first available.
 *
 * Accessing `$model->name` returns the translated string using the fallback chain.
 * Use `getTranslations()` to access the raw locale-keyed array.
 *
 * Note: `$model->name` returns a string, while `$model->getAttributes()['name']`
 * returns the raw JSON/array. Serialization via `toArray()` uses the cast (array),
 * not this accessor. Use {@see getTranslatedName()} for explicit locale control.
 */
trait HasTranslatableName
{
    /**
     * Override getAttribute so that `$model->name` returns the translated string.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if ($key === 'name') {
            return $this->getTranslatedName();
        }

        return parent::getAttribute($key);
    }

    /**
     * Get the translated name for a given locale.
     *
     * Falls back through: requested -> primary -> app -> fallback -> first available.
     *
     * @param  string|null  $locale  The locale to retrieve. Falls back if null or missing.
     */
    public function getTranslatedName(?string $locale = null): string
    {
        $translations = $this->getTranslations();

        if ($translations === []) {
            return '';
        }

        if ($locale !== null && isset($translations[$locale])) {
            return $translations[$locale];
        }

        $primaryConfig = config('laravel-turbo-tags.locale.primary');
        $primary = is_string($primaryConfig) ? $primaryConfig : app()->getLocale();

        if (isset($translations[$primary])) {
            return $translations[$primary];
        }

        $appLocale = app()->getLocale();
        if ($appLocale !== $primary && isset($translations[$appLocale])) {
            return $translations[$appLocale];
        }

        $fallback = config('laravel-turbo-tags.locale.fallback');
        if (is_string($fallback) && isset($translations[$fallback])) {
            return $translations[$fallback];
        }

        return reset($translations);
    }

    /**
     * Set the translated name for a specific locale.
     *
     * Merges into the existing translations without overwriting other locales.
     *
     * @param  string  $value  The translated name value.
     * @param  string|null  $locale  The locale to set. Defaults to primary or app locale.
     */
    public function setTranslatedName(string $value, ?string $locale = null): static
    {
        if ($locale === null) {
            $primaryConfig = config('laravel-turbo-tags.locale.primary');
            $locale = is_string($primaryConfig) ? $primaryConfig : app()->getLocale();
        }

        $translations = $this->getTranslations();
        $translations[$locale] = $value;

        $this->setAttribute('name', $translations);

        return $this;
    }

    /**
     * Get all translations as a locale-keyed array.
     *
     * Reads the raw attribute to avoid recursion with the name accessor.
     *
     * @return array<string, string>
     */
    public function getTranslations(): array
    {
        $name = $this->getAttributes()['name'] ?? null;

        if ($name === null) {
            return [];
        }

        if (is_string($name)) {
            /** @var array<string, string>|null $decoded */
            $decoded = json_decode($name, true);

            return is_array($decoded) ? $decoded : [];
        }

        if (is_array($name)) {
            /** @var array<string, string> $name */
            return $name;
        }

        return [];
    }

    /**
     * Check whether a translation exists for the given locale.
     *
     * @param  string  $locale  The locale to check.
     */
    public function hasTranslation(string $locale): bool
    {
        return isset($this->getTranslations()[$locale]);
    }
}
