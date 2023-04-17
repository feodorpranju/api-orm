<?php


namespace Feodorpranju\ApiOrm\Contracts;


interface ModelDeletableInterface extends ModelInterface
{
    /**
     * Deletes record
     *
     * @return void
     */
    public function delete(): void;
}