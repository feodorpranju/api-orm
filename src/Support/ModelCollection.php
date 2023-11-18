<?php


namespace Feodorpranju\ApiOrm\Support;


use Illuminate\Support\Collection;

class ModelCollection extends Collection
{
    /**
     * Filters items by conditions
     *
     * @param array|Collection $conditions
     * @return $this
     */
    public function filterByConditions(array|Collection $conditions): static
    {
        $collection = $this;

        foreach ($conditions as $condition) {
            $collection = $collection->where(...$condition);
        }

        return $collection;
    }
}