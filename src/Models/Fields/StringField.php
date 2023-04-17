<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;

class StringField extends AbstractField
{
    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value): ?string
    {
        return  is_null($value)
            ? null
            : $this->toString($value);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value): string
    {
        return (string)$value;
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value): ?string
    {
        return is_null($value)
        ? null
        : $this->toString($value);
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