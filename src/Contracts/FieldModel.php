<?php


namespace Feodorpranju\ApiOrm\Contracts;

use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;


interface FieldModel
{
    public function __construct(mixed $value, FieldSettings $settings);

    public function set(mixed $value): void;

    public function get(FieldGetMode $mode = null): mixed;

    public function toString(): string;

    public function toCrm(): mixed;

    public function toUsable(): mixed;
}