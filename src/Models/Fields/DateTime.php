<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Enumerations\fieldType;

use Carbon\Carbon;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;

class DateTime extends AbstractField
{
    protected static array $stringFormats = [
        "datetime" => "d.m.Y H:i:s",
        "date" => "d.m.Y",
        "time" => "H:i:s",
    ];

    protected static array $crmFormats = [
        "datetime" => "Y-m-d H:i:s",
        "date" => "Y-m-d",
        "time" => "H:i:s",
    ];

    public function toUsable(): Carbon
    {
        return new Carbon($this->value);
    }

    public function toString(): string
    {
        return $this->toUsable()->format(static::$stringFormats[$this->settings->type()->value] ?? "c");
    }

    public function toCrm(): string
    {
        return $this->toUsable()->format(static::$crmFormats[$this->settings->type()->value] ?? "c");
    }

    protected function validate(mixed $value = null): void
    {
        if (
            !is_string($value)
            && !is_a($value, Carbon::class, true)
            && !is_a($value, \DateTime::class, true)
        ) {
            throw new InvalidValueTypeException("Wrong type for field ".$this->settings->id());
        }
    }
}