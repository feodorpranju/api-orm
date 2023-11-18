<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Illuminate\Support\Collection;

interface ModelEditableInterface extends ModelInterface
{
    /**
     * Saves model changes or creates new record if not exist
     *
     * @return ModelEditableInterface
     */
    public function save(): static;
}