<?php


namespace Feodorpranju\ApiOrm\Contracts;


interface ModelEditableInterface
{
    /**
     * Saves model changes or creates new record if not exist
     *
     * @return ModelInterface
     */
    public static function save(): ModelInterface;
}