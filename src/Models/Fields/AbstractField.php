<?php


namespace Feodorpranju\ApiOrm\Models\Fields;


use Feodorpranju\ApiOrm\Contracts\FieldModel;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Feodorpranju\ApiOrm\Exceptions\Fields\ReadonlyUpdateException;
use Illuminate\Support\Collection;

abstract class AbstractField implements FieldModel
{
    public function __construct(protected mixed $value, protected FieldSettings $settings)
    {
        $this->validate($value);
    }

    /**
     * Sets field value
     *
     * @param mixed $value
     * @throws InvalidValueTypeException
     * @throws ReadonlyUpdateException\
     */
    public function set(mixed $value): void
    {
        if ($this->settings->readonly()) {
            throw new ReadonlyUpdateException("Update attempt for field ".$this->settings->id());
        }
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * Gets field value of provided mode
     *
     * @param FieldGetMode|null $mode
     * @return mixed|string|Collection
     */
    public function get(FieldGetMode $mode = null): mixed
    {
        return $this->settings->multiple()
            ? $this->multiple($mode)
            : $this->toMode($mode);
    }

    /**
     * Converts value to provided mode
     *
     * @param FieldGetMode|null $mode
     * @param mixed|null $value
     * @return mixed
     */
    protected function toMode(FieldGetMode $mode = null, mixed $value = null): mixed
    {
        return match ($mode) {
            FieldGetMode::Api => $this->toApi($value),
            FieldGetMode::String => $this->toString($value),
            default => $this->toUsable($value)
        };
    }

    /**
     * Converts value to string
     *
     * @param mixed|null $value
     * @return string|Collection
     */
    protected function toString(mixed $value = null): string|Collection
    {
        return $value ?? $this->value;
    }

    /**
     * Converts value to CRM acceptable mode
     *
     * @param mixed|null $value
     * @return mixed
     */
    protected function toApi(mixed $value = null): mixed
    {
        return $value ?? $this->value;
    }

    /**
     * Converts value to
     *
     * @param mixed|null $value
     * @return mixed
     */
    protected function toUsable(mixed $value = null): mixed
    {
        return $value ?? $this->value;
    }

    /**
     * Collects values converted to provided mode
     *
     * @param FieldGetMode|null $mode
     * @return Collection
     */
    protected function multiple(FieldGetMode $mode = null): Collection
    {
        $collection = new Collection();
        foreach ($this->value as $key => $value) {
            $collection[$key] = $this->toMode($mode, $value);
        }
        return $collection;
    }

    /**
     * Validates value checking settings.
     *
     * @param mixed|null $value
     * @throws InvalidValueTypeException
     */
    protected function validate(mixed $value = null): void
    {
        $value ??= $this->value;
        if ($this->settings->multiple()) {
            if (
                !is_array($value)
                && !is_a($value, Collection::class, true)
            ) {
                throw new InvalidValueTypeException("Multiple field must be array");
            }
            foreach ($value as $idx => $item) {
                $this->validateOne($item, $idx);
            }
        } else {
            $this->validateOne($value);
        }
    }

    /**
     * Validates value.
     * Does not check settings.
     *
     * @param mixed $value
     * @param int|null $idx
     */
    protected function validateOne(mixed $value, string $idx = null): void
    {

    }
}