<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Feodorpranju\ApiOrm\Models\Fields\Enumerations\Item;

class EnumField extends AbstractField
{
    public static bool $usableAsItem = false;

    /**
     * @inheritdoc
     * @see Item
     */
    protected function toUsable(mixed $value = null, ?bool $asItemForce = null): int|string|Item
    {
        $value ??= $this->value;
        $item = $this->settings->items()->firstWhere("id", $value);
        if (!$item) {
            $item = $this->settings->items()->firstOrFail("value", $value);
        }

        return (
            (
                $asItemForce === null
                && static::$usableAsItem
            ) || (
                $asItemForce !== null
                && $asItemForce
            )
        )
            ? $item
            : $item->value;
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value = null): string
    {
        return (string)($this->toUsable($value, true)->value);
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value = null): int|string
    {
        return $this->toUsable($value, true)->id;
    }

    /**
     * @inheritdoc
     * @throws InvalidValueTypeException
     */
    protected function validateOne(mixed $value = null, string $idx = null): void
    {
        $value ??= $this->value;
        if (
            !is_string($value)
            && !is_int($value)
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
    }
}