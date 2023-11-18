<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Illuminate\Support\Collection;

interface ModelCreatableInterface extends ModelInterface
{
    /**
     * Creates new record
     *
     * @param array|Collection $fields
     * @return static
     */
    public function create(array|Collection $fields): static;
}