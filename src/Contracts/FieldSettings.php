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

    /**
     * Gets field's multiple flag
     *
     * @return bool
     */
    public function multiple(): bool;

    /**
     * Gets field's readonly flag
     *
     * @return bool
     */
    public function readonly(): bool;

    /**
     * Gets field's type
     *
     * @return FieldType
     */
    public function type(): FieldType;

    /**
     * Gets field id
     *
     * @return string|int
     */
    public function id(): string|int;

    /**
     * Creates field's instance for setting's type
     *
     * @param mixed $value
     * @return FieldModel
     * @see FieldSettings::type()
     */
    public function field(mixed $value): FieldModel;
}