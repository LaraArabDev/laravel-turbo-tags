<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags\Models;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use LaraArabDev\TurboTags\Concerns\HasSlug;
use LaraArabDev\TurboTags\Concerns\HasTranslatableName;
use LaraArabDev\TurboTags\TagCache;

/**
 * Represents a tag that can be attached to any Eloquent model.
 *
 * Supports translatable names, automatic slug generation,
 * typed categorization, ordering, and metadata storage.
 *
 * @property int $id
 * @property array<string, string> $name
 * @property string $slug
 * @property string|null $type
 * @property int|null $order_column
 * @property array<string, mixed>|null $metadata
 * @property string|null $owner_type
 * @property int|null $owner_id
 * @property Model|null $owner
 * @property int|null $parent_id
 * @property Tag|null $parent
 * @property Collection<int, Tag> $children
 * @property Collection<int, Tag> $childrenRecursive
 * @property Carbon|null $deleted_at
 */
class Tag extends Model
{
    use HasSlug;
    use HasTranslatableName;
    use SoftDeletes;

    /** @var list<string> */
    protected $guarded = [];

    /**
     * Register model event listeners for hierarchy validation and cache invalidation.
     */
    protected static function booted(): void
    {
        static::saving(function (Tag $tag): void {
            if ($tag->parent_id !== null && $tag->hasCircularReference()) {
                throw new \InvalidArgumentException('Circular parent reference detected.');
            }
        });

        static::saved(fn () => TagCache::flush());
        static::deleted(fn () => TagCache::flush());
        static::restored(fn () => TagCache::flush());
    }

    /**
     * Get the attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'array',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the table name from config.
     */
    public function getTable(): string
    {
        $table = config('laravel-turbo-tags.tables.tags', 'tags');

        return is_string($table) ? $table : 'tags';
    }

    /**
     * Find an existing tag by name or create a new one.
     *
     * Results are cached when caching is enabled. Cache is automatically
     * invalidated when tags are created, updated, or deleted.
     *
     * @param  string  $name  The tag name to find or create.
     * @param  string|null  $type  Optional tag type/category.
     * @param  string|null  $locale  Optional locale for the tag name.
     */
    public static function findOrCreate(string $name, string|BackedEnum|null $type = null, ?string $locale = null): static
    {
        $type = self::resolveType($type);
        $locale = self::resolveLocale($locale);
        $cacheKey = 'find.'.md5("{$name}|{$type}|{$locale}");

        // Check cache first (returns null when disabled or missing)
        if (TagCache::has($cacheKey)) {
            /** @var static */
            return TagCache::get($cacheKey);
        }

        // Query database
        $tag = static::query()
            ->when($type !== null, fn (Builder $q) => $q->where('type', $type))
            ->where("name->{$locale}", $name)
            ->first();

        if ($tag !== null) {
            TagCache::put($cacheKey, $tag);

            return $tag;
        }

        // Create new tag (triggers saved event which flushes cache)
        $tag = static::query()->create([
            'name' => [$locale => $name],
            'type' => $type,
        ]);

        // Re-cache the newly created tag
        TagCache::put($cacheKey, $tag);

        return $tag;
    }

    /**
     * Find or create multiple tags at once.
     *
     * @param  array<int, string>  $names  The tag names to find or create.
     * @param  string|null  $type  Optional tag type/category.
     * @param  string|null  $locale  Optional locale for the tag names.
     * @return Collection<int, static>
     */
    public static function findOrCreateMany(array $names, string|BackedEnum|null $type = null, ?string $locale = null): Collection
    {
        $type = self::resolveType($type);
        $locale = self::resolveLocale($locale);

        // 1 query: batch find all existing tags
        $existing = static::query()
            ->when($type !== null, fn (Builder $q) => $q->where('type', $type))
            ->whereIn("name->{$locale}", $names)
            ->get()
            ->keyBy(fn (self $tag) => $tag->getTranslatedName($locale));

        $tags = new Collection;

        // Defer flush: N creates trigger only 1 cache flush at the end
        TagCache::withoutFlushing(function () use ($names, $existing, $type, $locale, $tags): void {
            foreach ($names as $name) {
                $found = $existing->get($name);

                // Create directly for missing — skip redundant find query
                $tags->push($found ?? static::query()->create([
                    'name' => [$locale => $name],
                    'type' => $type,
                ]));
            }
        });

        return $tags;
    }

    /**
     * Get all tags from cache or database.
     *
     * @return Collection<int, static>
     */
    public static function allCached(): Collection
    {
        $cached = TagCache::get('all');

        if ($cached instanceof Collection) {
            return $cached;
        }

        $tags = static::query()->get();
        TagCache::put('all', $tags);

        return $tags;
    }

    /**
     * Get all tags of a specific type from cache or database.
     *
     * @return Collection<int, static>
     */
    public static function allOfTypeCached(string|BackedEnum|null $type): Collection
    {
        $type = self::resolveType($type);
        $cacheKey = 'type.'.md5((string) $type);
        $cached = TagCache::get($cacheKey);

        if ($cached instanceof Collection) {
            return $cached;
        }

        $tags = static::query()->where('type', $type)->get();
        TagCache::put($cacheKey, $tags);

        return $tags;
    }

    /**
     * Search for tag suggestions matching a query string.
     *
     * Returns an empty collection if the search string is shorter
     * than the configured minimum length.
     *
     * @param  string  $search  The search query string.
     * @param  string|null  $type  Optional tag type to filter by.
     * @param  string|null  $locale  Optional locale to search in.
     * @param  int|null  $limit  Optional result limit. Defaults to config value.
     * @return Collection<int, static>
     */
    public static function suggestions(string $search, string|BackedEnum|null $type = null, ?string $locale = null, ?int $limit = null): Collection
    {
        $type = self::resolveType($type);
        $locale = self::resolveLocale($locale);
        $limit = self::resolveLimit($limit);

        $configMinLength = config('laravel-turbo-tags.suggestions.min_length', 2);
        $minLength = is_int($configMinLength) ? $configMinLength : 2;

        if (mb_strlen($search) < $minLength) {
            return new Collection;
        }

        $cacheKey = 'suggest.'.md5("{$search}|{$type}|{$locale}|{$limit}");
        $cached = TagCache::get($cacheKey);

        if ($cached instanceof Collection) {
            return $cached;
        }

        $tags = static::query()
            ->when($type !== null, fn (Builder $q) => $q->where('type', $type))
            ->where("name->{$locale}", 'like', "%{$search}%")
            ->limit($limit)
            ->get();

        TagCache::put($cacheKey, $tags);

        return $tags;
    }

    /**
     * Flush the entire tag cache.
     */
    public static function flushTagCache(): void
    {
        TagCache::flush();
    }

    /**
     * Get the owning model (user, company, etc.).
     *
     * @return MorphTo<Model, $this>
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the parent tag.
     *
     * @return BelongsTo<self, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the direct children tags.
     *
     * @return HasMany<self, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get all children recursively (eager-loadable).
     *
     * @return HasMany<self, $this>
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Scope query to root tags (no parent).
     *
     * @param  Builder<static>  $query
     */
    public function scopeRoots(Builder $query): void
    {
        $query->whereNull('parent_id');
    }

    /**
     * Determine if this tag is a root tag (has no parent).
     */
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Determine if this tag is a leaf tag (has no children).
     */
    public function isLeaf(): bool
    {
        return ! $this->children()->exists();
    }

    /**
     * Get all ancestors of this tag (walking up the parent chain).
     *
     * @return Collection<int, self>
     */
    public function ancestors(): Collection
    {
        $ancestors = new Collection;
        $current = $this->parent;
        $depth = 0;

        while ($current !== null && $depth++ < 100) {
            $ancestors->push($current);
            $current = $current->parent;
        }

        return $ancestors;
    }

    /**
     * Get all descendants of this tag (flattened from recursive children).
     *
     * @return Collection<int, self>
     */
    public function descendants(): Collection
    {
        $this->loadMissing('childrenRecursive');

        return $this->flattenChildren($this->childrenRecursive);
    }

    /**
     * Check if setting this tag's parent would create a circular reference.
     */
    protected function hasCircularReference(): bool
    {
        $current = self::withTrashed()->find($this->parent_id);
        $depth = 0;

        while ($current instanceof self && $depth++ < 100) {
            if ($current->id === $this->id) {
                return true;
            }
            $current = self::withTrashed()->find($current->parent_id);
        }

        return false;
    }

    /**
     * Recursively flatten a collection of children and their nested children.
     *
     * @param  Collection<int, self>  $children
     * @return Collection<int, self>
     */
    protected function flattenChildren(Collection $children): Collection
    {
        $result = new Collection;

        foreach ($children as $child) {
            $result->push($child);

            if ($child->relationLoaded('childrenRecursive') && $child->childrenRecursive->isNotEmpty()) {
                $result = $result->merge($this->flattenChildren($child->childrenRecursive));
            }
        }

        return $result;
    }

    /**
     * Scope query to global (unowned) tags.
     *
     * @param  Builder<static>  $query
     */
    public function scopeGlobal(Builder $query): void
    {
        $query->whereNull('owner_type');
    }

    /**
     * Scope query to tags owned by a specific model.
     *
     * @param  Builder<static>  $query
     * @param  Model  $owner  The owner model instance.
     */
    public function scopeOwnedBy(Builder $query, Model $owner): void
    {
        $query->whereMorphedTo('owner', $owner);
    }

    /**
     * Scope query to tags available to a specific model (global + owned).
     *
     * @param  Builder<static>  $query
     * @param  Model  $owner  The owner model instance.
     */
    public function scopeAvailableTo(Builder $query, Model $owner): void
    {
        $query->where(function (Builder $q) use ($owner) {
            $q->whereNull('owner_type')
                ->orWhereMorphedTo('owner', $owner);
        });
    }

    /**
     * Scope query to tags of a given type.
     *
     * @param  Builder<static>  $query
     * @param  string|null  $type  The tag type to filter by.
     */
    public function scopeOfType(Builder $query, string|BackedEnum|null $type): void
    {
        $query->where('type', self::resolveType($type));
    }

    /**
     * Scope query to tags whose name contains a search string.
     *
     * @param  Builder<static>  $query
     * @param  string  $search  The search string to match against.
     * @param  string|null  $locale  Optional locale to search in.
     */
    public function scopeContaining(Builder $query, string $search, ?string $locale = null): void
    {
        $locale = self::resolveLocale($locale);

        $query->where("name->{$locale}", 'like', "%{$search}%");
    }

    /**
     * Scope query to order by the order column.
     *
     * @param  Builder<static>  $query
     * @param  string  $direction  The sort direction ('asc' or 'desc').
     */
    public function scopeOrdered(Builder $query, string $direction = 'asc'): void
    {
        $query->orderBy('order_column', $direction);
    }

    /**
     * Scope query to find a tag by slug.
     *
     * @param  Builder<static>  $query
     * @param  string  $slug  The slug to search for.
     */
    public function scopeWithSlug(Builder $query, string $slug): void
    {
        $query->where('slug', $slug);
    }

    /**
     * Get models that are tagged with this tag.
     *
     * @template TModel of Model
     *
     * @param  class-string<TModel>  $modelClass  The fully qualified model class name.
     * @return MorphToMany<TModel, $this>
     */
    public function taggedModels(string $modelClass): MorphToMany
    {
        $table = config('laravel-turbo-tags.tables.taggables', 'taggables');

        return $this->morphedByMany(
            $modelClass,
            'taggable',
            is_string($table) ? $table : 'taggables',
        );
    }

    /**
     * Resolve a type parameter that may be a BackedEnum to its string value.
     */
    protected static function resolveType(string|BackedEnum|null $type): ?string
    {
        return $type instanceof BackedEnum ? (string) $type->value : $type;
    }

    /**
     * Resolve the locale, falling back to config primary or app locale.
     *
     * @param  string|null  $locale  The locale to resolve.
     */
    protected static function resolveLocale(?string $locale): string
    {
        if ($locale !== null) {
            return $locale;
        }

        $primary = config('laravel-turbo-tags.locale.primary');

        return is_string($primary) ? $primary : app()->getLocale();
    }

    /**
     * Resolve the suggestions limit from the given value or config.
     *
     * @param  int|null  $limit  The limit to resolve.
     */
    protected static function resolveLimit(?int $limit): int
    {
        if ($limit !== null) {
            return $limit;
        }

        $configLimit = config('laravel-turbo-tags.suggestions.limit', 10);

        return is_int($configLimit) ? $configLimit : 10;
    }
}
