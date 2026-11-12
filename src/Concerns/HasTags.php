<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
 *
 * Owned-tag helpers (tag owner = this model instance):
 * @method $this createTag(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null)
 * @method $this createTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null)
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
        $this->tags()->detach($this->resolveTagIds($tags, $type, $locale));

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

    /**
     * Find or create a tag owned by this model, then attach it.
     *
     * Unlike {@see attachTag()}, this method sets `owner_type` and `owner_id`
     * on the tag to this model instance, isolating it from other models' tags.
     *
     * When passed an existing Tag instance or integer ID, it is attached as-is
     * (ownership is not changed).
     */
    public function createTag(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        $this->tags()->syncWithoutDetaching($this->resolveOwnedTagIds($tags, $type, $locale));

        return $this;
    }

    /**
     * Find or create multiple tags owned by this model, then attach them.
     *
     * Alias for {@see createTag()} to match the plural convention.
     */
    public function createTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        return $this->createTag($tags, $type, $locale);
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
            ->whereIn("{$table}.id", $this->resolveTagIds($tag, $type, $locale))
            ->exists();
    }

    public function hasAllTags(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): bool
    {
        $table = $this->resolveTagsTable();
        $ids = $this->resolveTagIds($tags, $type, $locale);

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
        $ids = $this->resolveTagIds($tags, $type, $locale);

        $query->whereHas('tags', fn (Builder $q) => $q->where("{$table}.is_active", true)->whereIn("{$table}.id", $ids), '>=', \count($ids));
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithAnyTags(Builder $query, mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): void
    {
        $table = $this->resolveTagsTable();

        $query->whereHas('tags', fn (Builder $q) => $q->where("{$table}.is_active", true)->whereIn("{$table}.id", $this->resolveTagIds($tags, $type, $locale)));
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWithoutTags(Builder $query, mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): void
    {
        $table = $this->resolveTagsTable();

        $query->whereDoesntHave('tags', fn (Builder $q) => $q->where("{$table}.is_active", true)->whereIn("{$table}.id", $this->resolveTagIds($tags, $type, $locale)));
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
     * Resolve mixed input into owned tag IDs, creating missing tags with this model as owner.
     *
     * @return list<int>
     */
    protected function resolveOwnedTagIds(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): array
    {
        $type = $this->resolveType($type);
        $resolvedLocale = $this->resolveLocale($locale);

        $items = collect(match (true) {
            \is_array($tags) => $tags,
            $tags instanceof Collection => $tags->all(),
            default => [$tags],
        });

        /** @var list<string> $names */
        $names = $items->filter(fn (mixed $t): bool => \is_string($t))->unique()->values()->all();

        /** @var list<int> */
        return $items
            ->map(fn (mixed $t): ?int => $t instanceof Tag ? $t->id : (\is_int($t) ? $t : null))
            ->reject(fn (mixed $v): bool => $v === null)
            ->when($names !== [], fn ($ids) => $ids->merge(
                $this->findOrCreateOwnedTags($names, $type, $resolvedLocale)->pluck('id')
            ))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Find existing tags owned by this model or create new ones.
     *
     * @param  list<string>  $names
     * @return Collection<int, Tag>
     */
    protected function findOrCreateOwnedTags(array $names, ?string $type, string $locale): Collection
    {
        $tagModel = $this->getTagModel();

        $existing = $tagModel::query()
            ->where('is_active', true)
            ->where('owner_type', $this->getMorphClass())
            ->where('owner_id', $this->getKey())
            ->when($type !== null, fn (Builder $q) => $q->where('type', $type))
            ->whereIn("name->{$locale}", $names)
            ->get()
            ->keyBy(fn (Tag $tag): string => $tag->getTranslatedName($locale));

        $tags = new Collection;

        foreach ($names as $name) {
            $found = $existing->get($name);

            $tags->push($found ?? $tagModel::query()->create([
                'name' => [$locale => $name],
                'type' => $type,
                'owner_type' => $this->getMorphClass(),
                'owner_id' => $this->getKey(),
            ]));
        }

        return $tags;
    }

    /**
     * Resolve mixed input into unique tag IDs.
     *
     * @return list<int>
     */
    protected function resolveTagIds(mixed $tags, string|BackedEnum|null $type = null, ?string $locale = null): array
    {
        $type = $this->resolveType($type);

        $items = collect(match (true) {
            \is_array($tags) => $tags,
            $tags instanceof Collection => $tags->all(),
            default => [$tags],
        });

        /** @var list<string> $names */
        $names = $items->filter(fn (mixed $t): bool => \is_string($t))->values()->all();

        /** @var list<int> */
        return $items
            ->map(fn (mixed $t): ?int => $t instanceof Tag ? $t->id : (\is_int($t) ? $t : null))
            ->reject(fn (mixed $v): bool => $v === null)
            ->when($names !== [], fn ($ids) => $ids->merge(Tag::findOrCreateMany($names, $type, $locale)->pluck('id')))
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
