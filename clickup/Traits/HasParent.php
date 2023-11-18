<?php


namespace Feodorpranju\ClickUp\SDK\Traits;


use Feodorpranju\ApiOrm\Support\ModelCollection;
use Illuminate\Support\Str;

trait HasHierarchy
{
    public function getByParentId(int $parentId): ModelCollection
    {
        $parentClass = static::PARENT;

        return static::collect(
            $this->cmd(
                Str::replace('?', $parentId, static::GET_BY_PARENT_PATTERN),
                'get'
            )
                ->call()
                ->getResult()
                ->get('spaces')
        )->map(
            fn($space) => $space->put([
                $parentClass::ENTITY.'_id' => $parentId
            ])
        );
    }

    public function getByParentIds(array $parentIds): ModelCollection
    {
        $spaces = collect();

        foreach ($parentIds as $parentId) {
            $spaces = $spaces->merge(
                $this->getByParentId($parentId)
            );
        }

        return ModelCollection::make($spaces);
    }

    public function getByParentsRecursive(array &$conditions): ModelCollection
    {
        
    }
}