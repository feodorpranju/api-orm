<?php


namespace Feodorpranju\ApiOrm\Contracts;


interface ModelDeletableInterface
{
    /**
     * Deletes record
     *
     * @return ModelInterface
     */
    public static function delete(): ModelInterface;
}