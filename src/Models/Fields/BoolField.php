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
    protected function toUsable(mixed $value = null): bool
    {
        $value ??= $this->value;
        return (is_bool($value) && $value)
            || in_array(Str::lower((string)$value), static::$trueCases);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value = null): string
    {
        return $this->toUsable($value) ? "1" : "0";
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value = null): int|bool|string
    {
        return $this->toUsable($value);
    }

    /**
     * @inheritdoc
     * @throws InvalidValueTypeException
     */
    protected function validateOne(mixed $value, string $idx = null): void
    {
        $value ??= $this->value;
        if (
            !is_string($value)
            && !is_int($value)
            && !is_bool($value)
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
    }
}