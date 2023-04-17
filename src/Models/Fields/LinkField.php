<?php


namespace Feodorpranju\ApiOrm\Models\Fields;

use Feodorpranju\ApiOrm\Contracts\ModelInterface;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Feodorpranju\ApiOrm\Exceptions\Fields\ModelDoesNotExistException;

class LinkField extends AbstractField
{
    /**
     * @inheritdoc
     */
    protected function toUsable(mixed $value): ModelInterface
    {
        if (!class_exists($this->settings->model())) {
            throw new ModelDoesNotExistException("Class {$this->settings->model()} does not exist");
        }
        return $this->settings->model()::get($this->toString($value));
    }

    /**
     * @inheritdoc
     */
    protected function toString(mixed $value): string
    {
        return (int)$value;
    }

    /**
     * @inheritdoc
     */
    protected function toApi(mixed $value): int|string
    {
        return (int)$this->toString($value);
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
}