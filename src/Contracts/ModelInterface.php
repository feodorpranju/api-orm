<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Illuminate\Support\Collection;

interface ModelInterface
{
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
}