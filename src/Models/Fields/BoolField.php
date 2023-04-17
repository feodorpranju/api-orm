<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Illuminate\Support\Str;

class BoolField extends AbstractField
{
    protected static array $trueCases = ["1", "ok", "true", "yes", "y", "valid", "success"];

    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value): bool
    {
        return (is_bool($value) && $value)
            || in_array(Str::lower((string)$value), static::$trueCases);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value): string
    {
        return $this->toUsable($value) ? "1" : "0";
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value): int|bool|string
    {
        return $this->toUsable($value);
    }

    /**
     * @inheritdoc
     * @throws InvalidValueTypeException
     */
    protected function validateOne(mixed $value, string $idx = null): void
    {
        if (
            !is_string($value)
            && !is_int($value)
            && !is_bool($value)
            && !is_null($value)
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
    }
}