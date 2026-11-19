<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection as BaseCollection;
use LaraArabDev\TurboTags\Models\Tag;

/**
 * Provides polymorphic tagging capabilities to Eloquent models.
 *
 * @mixin Model
 *
 * @method static Builder<static> withAllTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null)
 * @method static Builder<static> withAnyTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null)
 * @method static Builder<static> withoutTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null)
 * @method static Builder<static> withTagsOfType(string|BackedEnum $type)
 * @method static Builder<static> withTagsLoaded()
 * @method static Builder<static> withTagCount()
 */
trait HasTags
{
    /**
     * @return MorphToMany<Tag, $this>
     */
    public function tags(): MorphToMany
    {
        $table = config('laravel-turbo-tags.tables.taggables', 'taggables');

        return $this->morphToMany(
            $this->getTagModel(),
            'taggable', // Morph type — must stay 'taggable' regardless of table name config
            \is_string($table) ? $table : 'taggables',
        )->withTimestamps();
    }

    /**
     * Resolve the configured tags table name.
     */
    protected function resolveTagsTable(): string
    {
        $table = config('laravel-turbo-tags.tables.tags', 'tags');

        return \is_string($table) ? $table : 'tags';
    }

    /**
     * @return MorphToMany<Tag, $this>
     */
    public function tagsOfType(string|BackedEnum $type): MorphToMany
    {
        return $this->tags()->where('type', $this->resolveType($type));
    }

    public function attachTag(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        $this->tags()->syncWithoutDetaching($this->resolveTagIds($tags, $type, $locale));

        return $this;
    }

    public function attachTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        return $this->attachTag($tags, $type, $locale);
    }

    public function detachTag(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        $this->tags()->detach($this->resolveExistingTagIds($tags, $type, $locale));

        return $this;
    }

    public function detachTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        return $this->detachTag($tags, $type, $locale);
    }

    public function removeAllTags(): static
    {
        $this->tags()->detach();

        return $this;
    }

    public function syncTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        $type = $this->resolveType($type);
        $ids = $this->resolveTagIds($tags, $type, $locale);

        if ($type === null) {
            $this->tags()->sync($ids);

            return $this;
        }

        $table = $this->resolveTagsTable();

        /** @var list<int> $keepIds */
        $keepIds = $this->tags()
            ->where(fn (Builder $q) => $q->whereNull("{$table}.type")->orWhere("{$table}.type", '!=', $type))
            ->pluck("{$table}.id")
            ->all();

        $this->tags()->sync([...$keepIds, ...$ids]);

        return $this;
    }

    public function syncTagsWithType(mixed $tags, string|BackedEnum $type, ?string $locale = null): static
    {
        return $this->syncTags($tags, $type, $locale);
    }

    public function hasTag(mixed $tag, string|BackedEnum|null $type = null, ?string $locale = null): bool
    {
        $table = $this->resolveTagsTable();

        return $this->tags()
            ->whereIn("{$table}.id", $this->resolveExistingTagIds($tag, $type, $locale))
            ->exists();
    }

    public function hasAllTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): bool
    {
        $table = $this->resolveTagsTable();

        $uniqueInputCount = collect(match (true) {
            \is_array($tags) => $tags,
            $tags instanceof BaseCollection => $tags->all(),
            default => [$tags],
        })->unique()->count();

        $ids = $this->resolveExistingTagIds($tags, $type, $locale);

        // If fewer IDs resolved than items requested, some tags don't exist → cannot have all
        if (\count($ids) < $uniqueInputCount) {
            return false;
        }

        return $this->tags()->whereIn("{$table}.id", $ids)->count() === \count($ids);
    }

    public function hasAnyTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): bool
    {
        return $this->hasTag($tags, $type, $locale);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithAllTags(Builder $query, mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): void
    {
        $table = $this->resolveTagsTable();

        $uniqueInputCount = collect(match (true) {
            \is_array($tags) => $tags,
            $tags instanceof BaseCollection => $tags->all(),
            default => [$tags],
        })->unique()->count();

        $ids = $this->resolveExistingTagIds($tags, $type, $locale);

        // Some requested tags don't exist as active tags — no model can have all of them
        if (\count($ids) < $uniqueInputCount) {
            $query->whereRaw('0 = 1');

            return;
        }

        $query->whereHas('tags', fn (Builder $q) => $q->where("{$table}.is_active", true)->whereIn("{$table}.id", $ids), '>=', \count($ids));
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithAnyTags(Builder $query, mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): void
    {
        $table = $this->resolveTagsTable();

        $query->whereHas('tags', fn (Builder $q) => $q->where("{$table}.is_active", true)->whereIn("{$table}.id", $this->resolveExistingTagIds($tags, $type, $locale)));
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithoutTags(Builder $query, mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): void
    {
        $table = $this->resolveTagsTable();

        $query->whereDoesntHave('tags', fn (Builder $q) => $q->where("{$table}.is_active", true)->whereIn("{$table}.id", $this->resolveExistingTagIds($tags, $type, $locale)));
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithTagsOfType(Builder $query, string|BackedEnum $type): void
    {
        $query->whereHas('tags', fn (Builder $q) => $q->where('type', $this->resolveType($type)));
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithTagsLoaded(Builder $query): void
    {
        $query->with('tags');
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithTagCount(Builder $query): void
    {
        $query->withCount('tags');
    }

    /**
     * Resolve mixed input into existing tag IDs — never creates tags.
     *
     * Used by all read-only operations (hasTag, scopes, detachTag) so they
     * cannot create orphan tags as a side effect.
     *
     * @return list<int>
     */
    protected function resolveExistingTagIds(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): array
    {
        $type = $this->resolveType($type);
        $resolvedLocale = $this->resolveLocale($locale);
        $tagModel = $this->getTagModel();

        $items = collect(match (true) {
            \is_array($tags) => $tags,
            $tags instanceof BaseCollection => $tags->all(),
            default => [$tags],
        });

        /** @var list<string> $names */
        $names = $items->filter(fn (mixed $t): bool => \is_string($t))->values()->all();

        /** @var list<int> */
        return $items
            ->map(fn (mixed $t): ?int => $t instanceof Tag ? $t->id : (\is_int($t) ? $t : null))
            ->reject(fn (mixed $v): bool => $v === null)
            ->when($names !== [], fn ($ids) => $ids->merge(
                $tagModel::query()
                    ->where('is_active', true)
                    ->where('type', $type)
                    ->whereIn("name->{$resolvedLocale}", $names)
                    ->pluck('id')
            ))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Resolve mixed input into tag IDs, creating tags for missing string names.
     *
     * Used only by write operations (attachTag, syncTags).
     *
     * @return list<int>
     */
    protected function resolveTagIds(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): array
    {
        $type = $this->resolveType($type);

        $items = collect(match (true) {
            \is_array($tags) => $tags,
            $tags instanceof BaseCollection => $tags->all(),
            default => [$tags],
        });

        /** @var list<string> $names */
        $names = $items->filter(fn (mixed $t): bool => \is_string($t))->values()->all();

        $tagModel = $this->getTagModel();

        /** @var list<int> */
        return $items
            ->map(fn (mixed $t): ?int => $t instanceof Tag ? $t->id : (\is_int($t) ? $t : null))
            ->reject(fn (mixed $v): bool => $v === null)
            ->when($names !== [], fn ($ids) => $ids->merge($tagModel::findOrCreateMany($names, $type, $locale)->pluck('id')))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Resolve a type parameter that may be a BackedEnum to its string value.
     */
    protected function resolveType(string|BackedEnum|null $type): ?string
    {
        return $type instanceof BackedEnum ? (string) $type->value : $type;
    }

    /**
     * Resolve the locale, falling back to the config primary or app locale.
     */
    protected function resolveLocale(?string $locale): string
    {
        if ($locale !== null) {
            return $locale;
        }

        $primary = config('laravel-turbo-tags.locale.primary');

        return \is_string($primary) ? $primary : app()->getLocale();
    }

    /**
     * @return class-string<Tag>
     */
    protected function getTagModel(): string
    {
        /** @var class-string<Tag> */
        return config('laravel-turbo-tags.tag_model', Tag::class);
    }
}
