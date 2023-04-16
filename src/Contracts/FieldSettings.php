<?php


namespace Feodorpranju\ApiOrm\Contracts;

use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Models\Fields\AbstractField;
use Illuminate\Support\Collection;

interface FieldSettings
{
    public function __construct(
        mixed $id,
        ?FieldType $type = null,
        bool $multiple = false,
        bool $readonly = false
    );

    /**
     * Sets enumeration items
     *
     * @param array|Collection $items
     * @return FieldSettings
     */
    public function setItems(array|Collection $items): static;

    /**
     * Gets enumeration items
     *
     * @return Collection
     */
    public function items(): Collection;

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

    /**
     * Gets class of linked model
     *
     * @return string|null
     */
    public function model(): ?string;
}