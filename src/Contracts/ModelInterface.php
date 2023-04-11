<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Illuminate\Support\Collection;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;

interface ModelInterface
{
    /**
     * Creates Model and sets it's fields
     *
     * @param array|Collection $attributes
     */
    public function __construct(array|Collection $attributes = []);

    /**
     * Gets record from bitrix24 as model
     *
     * @param int $id Entity id
     * @return ModelInterface
     */
    public static function get(int $id): ModelInterface;

    /**
     * Returns entity name
     *
     * @return string
     */
    public static function entity(): string;

    /**
     * Gets collection of field settings
     *
     * @return Collection
     * @see FieldSettings
     */
    public static function fields(): Collection;

    /**
     * Creates query builder object for current model
     *
     * @param array|null $fields
     * @return QueryBuilderInterface
     */
    public static function select(array $fields = null): QueryBuilderInterface;

    /**
     * @param string $name
     * @param FieldGetMode|null $mode
     * @return mixed
     */
    public function getAs(string $name, FieldGetMode $mode = null): mixed;

    /**
     * Gets collection of given fields. All if null;
     *
     * @param array|null $names
     * @param FieldGetMode|null $mode
     * @return Collection
     */
    public function only(?array $names = null, ?FieldGetMode $mode = null): Collection;

    /**
     * Finds items by filter
     *
     * @param array|Collection $conditions
     * @param string|null $orderBy
     * @param string|null $orderDirection
     * @param int $offset
     * @param int $limit
     * @return Collection|false
     */
    public static function find(
        array|Collection $conditions = [],
        string $orderBy = null,
        string $orderDirection = null,
        int $offset = 0,
        int $limit = 50
    ): Collection|false;

    /**
     * Gets record count by filter
     *
     * @param array|Collection $conditions
     * @return int
     */
    public static function count(array|Collection $conditions): int;
}