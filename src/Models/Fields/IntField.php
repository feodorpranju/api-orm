<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;

class IntField extends AbstractField
{
    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value): int
    {
        return (int)$value;
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value): string
    {
        return is_null($value)
            ? ""
            : (string)$this->toUsable($value);
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value): ?int
    {
        return is_null($value)
            ? null
            : $this->toUsable($value);
    }

    /**
     * @inheritdoc
     * @throws InvalidValueTypeException
     */
    protected function validateOne(mixed $value, string $idx = null): void
    {
        if (
            !is_string($value)
            && !is_float($value)
            && !is_int($value)
            && !is_null($value)
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
    }
}