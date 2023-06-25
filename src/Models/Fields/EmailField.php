<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\EmailValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Feodorpranju\ApiOrm\Exceptions\Fields\UndefinedFieldTypeException;
use Feodorpranju\ApiOrm\Exceptions\Fields\ValidationException;

class EmailField extends AbstractField
{
    /**
     * @inheritdoc
     */
    public const IS_EMPTY_STRING_UNDEFINED = true;

    protected static MultipleValidationWithAnd $validations;

    public function __construct(protected mixed $value, protected FieldSettings $settings)
    {
        if (!isset(static::$validations)) {
            static::setValidations([new RFCValidation()]);
        }
        parent::__construct($value, $settings);
    }

    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value): string
    {
        return $this->toString($value);
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value): string
    {
        return trim($value);
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
     * @throws InvalidValueTypeException|ValidationException
     */
    protected function validateOne(mixed $value, string $idx = null): void
    {
        $value = trim($value);
        if (
            !is_string($value)
        ) {
            throw new InvalidValueTypeException("Wrong type for field "
                .$this->settings->id()
                .($idx !== null ? "at index $idx" : ""));
        }
        $validator = new EmailValidator();
        if (!$validator->isValid($value, static::$validations)) {
            throw new ValidationException(
                $validator->getError()->description(),
                $validator->getError()->code()
            );
        }
    }

    /**
     * Sets email validations
     *
     * @param array|null $validations
     * @throws UndefinedFieldTypeException
     * @see EmailValidation
     */
    public static function setValidations(array $validations = null): void
    {
        $validations ??= [];
        foreach ($validations as $validation) {
            if (is_a($validation, EmailValidation::class, true)) {
                if (is_string($validation)) {
                    $validations[] = new $validation();
                    continue;
                }
                if (is_object($validation)) {
                    $validations[] = $validation;
                    continue;
                }
            }
            throw new UndefinedFieldTypeException((string)$validation);
        }
        static::$validations = new MultipleValidationWithAnd($validations);
    }
}