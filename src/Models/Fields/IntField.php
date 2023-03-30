<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Enumerations\fieldType;

use Carbon\Carbon;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Illuminate\Support\Collection;

class IntField extends AbstractField
{
    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value = null): int
    {
        return (int)($value ?? $this->value);
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
    protected function toApi(mixed $value = null): int
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
        ) {
            throw new InvalidValueTypeException("Wrong type for field ".$this->settings->id());
        }
    }
}