<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Brick\PhoneNumber\PhoneNumberParseException;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;

class PhoneField extends AbstractField
{
    /**
     * @inheritdoc
     */
    public const IS_EMPTY_STRING_UNDEFINED = true;

    public static int $phoneNumberFormat = PhoneNumberFormat::INTERNATIONAL;
    public static bool $usableAsObject = false;

    /**
     * @inheritdoc
     * @param bool|null $asObjectForce force as object value.
     * If not null ignores $usableAsObject
     * @throws PhoneNumberParseException
     * @see PhoneField::$usableAsObject
     */
    protected function toUsable(mixed $value = null, ?bool $asObjectForce = null): string|PhoneNumber
    {
        $phone = is_a($value, PhoneNumber::class, true)
            ? $value : PhoneNumber::parse($this->prepare($value));

        return (
            (
                $asObjectForce === null
                && static::$usableAsObject
            ) || (
                $asObjectForce !== null
                && $asObjectForce
            )
        )
            ? $phone
            : $phone->format(static::$phoneNumberFormat);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value): string
    {
        return strval($this->toUsable($value, false));
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value): mixed
    {
        return $this->toString($value);
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
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
    }

    /**
     * Prepares value before providing it into parser
     *
     * @param string|int $value
     * @return string
     */
    protected function prepare(string|int $value): string
    {
        $raw = preg_replace('/[^0-9.]+/', '', (string)$value);
        if (strlen($raw) === 10) {
            $raw = '7'.$raw;
        } elseif (strlen($raw) > 10 && $raw[0] === '8') {
            $raw[0] = '7';
        }
        return "+".$raw;
    }
}