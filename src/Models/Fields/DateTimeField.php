<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use DateTime;
use Carbon\Carbon;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;

class DateTimeField extends AbstractField
{
    protected static array $stringFormats = [
        "datetime" => "d.m.Y H:i:s",
        "date" => "d.m.Y",
        "time" => "H:i:s",
    ];

    protected static array $apiFormats = [
        "datetime" => "Y-m-d H:i:s",
        "date" => "Y-m-d",
        "time" => "H:i:s",
    ];

    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value = null): Carbon
    {
        return new Carbon($value ?? $this->value);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value = null): string
    {
        return $this->toUsable($value ?? $this->value)->format(static::$stringFormats[$this->settings->type()->value] ?? "c");
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value = null): string
    {
        return $this->toUsable($value ?? $this->value)->format(static::$apiFormats[$this->settings->type()->value] ?? "c");
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
            && !is_a($value, Carbon::class, true)
            && !is_a($value, DateTime::class, true)
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
    }
}