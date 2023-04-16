<?php


namespace Feodorpranju\ApiOrm\Contracts;


interface ModelEditableInterface
{
    /**
     * Saves model changes or creates new record if not exist
     *
     * @return ModelEditableInterface
     */
    public function save(): static;
}