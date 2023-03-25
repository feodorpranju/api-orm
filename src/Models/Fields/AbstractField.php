<?php


namespace Feodorpranju\ApiOrm\Models\Fields;


use Feodorpranju\ApiOrm\Contracts\FieldModel;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Feodorpranju\ApiOrm\Exceptions\Fields\ReadonlyUpdateException;

abstract class AbstractField implements FieldModel
{
    public function __construct(protected mixed $value, protected FieldSettings $settings)
    {
        $this->validate($value);
    }

    public function set(mixed $value): void
    {
        if ($this->settings->readonly()) {
            throw new ReadonlyUpdateException("Update attempt for field ".$this->settings->id());
        }
        $this->validate($value);
        $this->value = $value;
    }

    public function get(FieldGetMode $mode = null): mixed
    {
        return match ($mode) {
            FieldGetMode::Crm => $this->toCrm(),
            FieldGetMode::String => $this->toString(),
            FieldGetMode::Usable => $this->toUsable(),
            default => $this->toUsable()
        };
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toCrm(): mixed
    {
        return $this->value;
    }

    public function toUsable(): mixed
    {
        return $this->value;
    }

    protected function validate(mixed $value = null): void
    {

    }
}