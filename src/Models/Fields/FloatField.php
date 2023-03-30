<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Enumerations\fieldType;

use Carbon\Carbon;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Illuminate\Support\Collection;

class FloatField extends AbstractField
{
    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value = null): float
    {
        return (float)($value ?? $this->value);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value = null): string
    {
        return (string)($value ?? $this->value);
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value = null): float
    {
        return $this->toUsable($value);
    }

    /**g
     * @inheritdoc
     */
    protected function validate(mixed $value = null): void
    {
        $value ??= $this->value;
        parent::validate();
        if (
            !$this->settings->multiple()
            && !is_string($value)
            && !is_int($value)
            && !is_float($value)
        ) {
            throw new InvalidValueTypeException("Wrong type for field ".$this->settings->id());
        }
    }
}