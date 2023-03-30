<?php


namespace Feodorpranju\ApiOrm\Contracts;

use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Illuminate\Support\Collection;


interface FieldModel
{
    public function __construct(mixed $value, FieldSettings $settings);

    public function set(mixed $value): void;

    public function get(FieldGetMode $mode = null): mixed;
}