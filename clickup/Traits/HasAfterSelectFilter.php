<?php


namespace Feodorpranju\ClickUp\SDK\Traits;


use Illuminate\Support\Collection;

trait AfterSelectFilter
{
    public static function find(array|Collection $conditions = [], string $orderBy = null, string $orderDirection = null, array $select = [], int $offset = 0, int $limit = 50): Collection
    {
        $teams = static::all();

        foreach ($conditions as $condition) {
            $teams = $teams->where(...$condition);
        }

        return $teams;
    }
}