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
    protected function toUsable(mixed $value): ?Carbon
    {
        return empty($value)
            ? null
            : new Carbon($value);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value): string
    {
        return empty($value)
            ? ""
            : $this->toUsable($value)->format(static::$stringFormats[$this->settings->type()->value] ?? "c");
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value): ?string
    {
        return empty($value)
            ? null
            : $this->toUsable($value)->format(static::$apiFormats[$this->settings->type()->value] ?? "c");
    }

    /**
     * @inheritdoc
     * @throws InvalidValueTypeException
     */
    protected function validateOne(mixed $value, string $idx = null): void
    {
        if (
            !is_string($value)
            && !is_a($value, Carbon::class, true)
            && !is_a($value, DateTime::class, true)
            && !is_null($value)
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
    }
}