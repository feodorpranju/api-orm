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
     *
     * @return array
     */
    public function getFields(): array;


    /**
     * Gets model field by name
     *
     * @param string $name
     * @return mixed
     */
    public function getField(string $name): mixed;
}