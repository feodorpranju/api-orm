<?php


namespace Feodorpranju\ApiOrm\Contracts;


interface ModelDeletableInterface
{
    /**
     * Deletes record
     *
     * @return void
     */
    public static function delete(): void;
}