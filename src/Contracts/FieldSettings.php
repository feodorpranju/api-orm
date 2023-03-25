<?php


namespace Feodorpranju\ApiOrm\Contracts;

use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Models\Fields\AbstractField;

interface FieldSettings
{
    public function __construct(
        string|int $id,
        FieldType $type,
        bool $multiple = false,
        bool $readonly = false
    );

    public function multiple(): bool;

    public function readonly(): bool;

    public function type(): FieldType;

    public function id(): string|int;
}