<?php

declare(strict_types=1);

namespace LaraArabDev\TurboTags\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Provides tag usage reporting capabilities.
 *
 * Adds static methods for retrieving most popular, recent,
 * and trending tags based on taggable pivot data.
 *
 * @mixin Model
 */
trait HasReporting
{
    /**
     * Get the most popular tags ordered by usage count.
     *
     * Each returned tag has a `taggables_count` attribute.
     *
     * @param  int  $limit  Maximum number of tags to return.
     * @param  string|BackedEnum|null  $type  Optional tag type to filter by.
     * @return Collection<int, static>
     */
    public static function mostPopular(int $limit = 10, string|BackedEnum|null $type = null): Collection
    {
        $type = self::resolveType($type);
        $tagsTable = self::resolveTagsTable();
        $taggablesTable = self::resolveTaggablesTable();

        return static::query()
            ->select("{$tagsTable}.*")
            ->selectSub(
                DB::table($taggablesTable)->selectRaw('count(*)')->whereColumn('tag_id', "{$tagsTable}.id"),
                'taggables_count',
            )
            ->when($type !== null, fn (Builder $q) => $q->where('type', $type))
            ->orderByDesc('taggables_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get the most recently created tags.
     *
     * @param  int  $limit  Maximum number of tags to return.
     * @param  string|BackedEnum|null  $type  Optional tag type to filter by.
     * @return Collection<int, static>
     */
    public static function recent(int $limit = 10, string|BackedEnum|null $type = null): Collection
    {
        $type = self::resolveType($type);

        return static::query()
            ->when($type !== null, fn (Builder $q) => $q->where('type', $type))
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get the most popular tags based on recent usage within a time window.
     *
     * Only counts tagging activity from the last N days. Each returned tag
     * has a `taggables_count` attribute reflecting recent usage only.
     *
     * @param  int  $limit  Maximum number of tags to return.
     * @param  int  $days  Number of days to look back for recent usage.
     * @param  string|BackedEnum|null  $type  Optional tag type to filter by.
     * @return Collection<int, static>
     */
    public static function recentMostPopular(int $limit = 10, int $days = 30, string|BackedEnum|null $type = null): Collection
    {
        $type = self::resolveType($type);
        $tagsTable = self::resolveTagsTable();
        $taggablesTable = self::resolveTaggablesTable();
        $since = Carbon::now()->subDays($days);

        return static::query()
            ->select("{$tagsTable}.*")
            ->selectSub(
                DB::table($taggablesTable)
                    ->selectRaw('count(*)')
                    ->whereColumn('tag_id', "{$tagsTable}.id")
                    ->where('created_at', '>=', $since),
                'taggables_count',
            )
            ->whereExists(
                fn (QueryBuilder $q) => $q->from($taggablesTable)
                    ->whereColumn('tag_id', "{$tagsTable}.id")
                    ->where('created_at', '>=', $since),
            )
            ->when($type !== null, fn (Builder $q) => $q->where('type', $type))
            ->orderByDesc('taggables_count')
            ->limit($limit)
            ->get();
    }
}
