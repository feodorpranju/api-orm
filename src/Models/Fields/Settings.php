<?php


namespace Feodorpranju\ApiOrm\Models\Fields;


use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;

class Settings implements FieldSettings
{
    public function __construct(
        protected string|int $id,
        protected FieldType $type,
        protected bool $multiple = false,
        protected bool $readonly = false
    ){}

    public function multiple(): bool
    {
        return $this->multiple;
    }

    public function readonly(): bool
    {
        return $this->readonly;
    }

    public function type(): FieldType
    {
        return $this->type;
    }

    public function id(): string|int
    {
        return $this->id;
    }
}