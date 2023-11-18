<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Feodorpranju\ApiOrm\Support\ModelCollection;
use Illuminate\Support\Collection;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;

interface ModelSearchableInterface extends ModelInterface
{
    /**
     * Creates query builder object for current model
     *
     * @param array|null $fields
     * @return QueryBuilderInterface
     */
    public static function select(array $fields = null): QueryBuilderInterface;

    /**
     * Creates query builder object for current model with condition
     *
     * @param string|array $field
     * @param mixed|null $operand
     * @param mixed|null $value
     * @return QueryBuilderInterface
     *
     * @see QueryBuilderInterface::where()
     */
    public static function where(string|array $field, mixed $operand = null, mixed $value = null): QueryBuilderInterface;

    /**
     * Gets collection of fields except given ones. All if empty;
     *
     * @param array $names
     * @param FieldGetMode|null $mode
     * @return Collection
     */
    public function except(array $names = [], ?FieldGetMode $mode = null): Collection;

    /**
     * Finds items by filter
     *
     * @param array|Collection $conditions List of conditions [[field, operator, value]...]
     * @param string|null $orderBy Field name
     * @param string|null $orderDirection ASC|DESC
     * @param array $select
     * @param int $offset
     * @param int $limit
     * @return ModelCollection
     */
    public function find(
        array|Collection $conditions = [],
        string $orderBy = null,
        string $orderDirection = null,
        array $select = [],
        int $offset = 0,
        int $limit = 50
    ): ModelCollection;

    /**
     * Gets record count by filter
     *
     * @param array|Collection $conditions
     * @return int
     */
    public function count(array|Collection $conditions): int;

    /**
     * Selects all fields of all items
     *
     * @return ModelCollection
     */
    public function all(): ModelCollection;
}