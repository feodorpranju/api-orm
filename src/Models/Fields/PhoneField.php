<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;

use Brick\PhoneNumber\PhoneNumberParseException;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;

class PhoneField extends AbstractField
{
    protected static int $phoneNumberFormat = PhoneNumberFormat::INTERNATIONAL;
    protected static bool $usableAsObject = false;

    /**
     * @inheritdoc
     * @param bool|null $asObjectForce force as object value.
     * If not null ignores $usableAsObject
     * @throws PhoneNumberParseException
     * @see PhoneField::$usableAsObject
     */
    protected function toUsable(mixed $value = null, ?bool $asObjectForce = null): string|PhoneNumber
    {
        $value ??= $this->value;
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
    protected function toString(mixed $value = null): string
    {
        return $this->toUsable($value, false);
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value = null): mixed
    {
        return $this->toString($value);
    }

    /**
     * @inheritdoc
     * @throws InvalidValueTypeException
     */
    protected function validateOne(mixed $value = null, string $idx = null): void
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
     * Sets format of phone returning as string
     *
     * @param int $format
     * @see PhoneNumberFormat
     */
    public static function setFormat(int $format) {
        static::$phoneNumberFormat = $format;
    }

    /**
     * Sets default mode for usable value
     *
     * @param bool $usableAsObject
     */
    public static function setUsableAsObject(bool $usableAsObject) {
        static::$usableAsObject = $usableAsObject;
    }

    /**
     * Prepares value before providing it into parser
     *
     * @param string|int $value
     * @return string
     */
    protected function prepare(string|int $value): string
    {
        return "+".preg_replace('/[^0-9.]+/', '', (string)$value);
    }
}