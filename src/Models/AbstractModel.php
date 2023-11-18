<?php


namespace Feodorpranju\ApiOrm\Models;


use Feodorpranju\ApiOrm\Contracts\Http\ApiClientInterface;
use Feodorpranju\ApiOrm\Contracts\ModelInterface;
use Feodorpranju\ApiOrm\Models\Fields\DefaultField;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Feodorpranju\ApiOrm\Support\ModelCollection;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;

abstract class AbstractModel implements ModelInterface
{
    protected Collection $_attributes;
    protected Collection $_rawAttributes;
    protected static Collection $_fields;
    protected array $updatedFields = [];
    protected const SET_FIELDS_ON_CONSTRUCT = false;
    protected const DEFAULT_GET_MODE = FieldGetMode::Usable;
    protected static array $_readonlyFields;
    /**
     * @var ApiClientInterface|mixed|null
     */

    protected ?ApiClientInterface $client = null;

    /**
     * @inheritdoc
     */
    public function __construct(array|Collection $attributes = [])
    {
        $this->_attributes = collect([]);
        if (static::SET_FIELDS_ON_CONSTRUCT) {
            $this->put($attributes);
        }
        $this->_rawAttributes = collect($attributes);
        $this->updatedFields = [];
    }

    /**
     * @inheritdoc
     */
    public static function make(array|Collection $attributes = [], ?ApiClientInterface $client = null): static
    {
        return (new static($attributes))->setClient($client);
    }

    /**
     * @inheritdoc
     */
    public function fields(): Collection
    {
        return static::$_fields ??= collect([]);
    }

    /**
     * @inheritdoc
     */
    public function put(array|Collection $values): static
    {
        $this->_attributes ??= collect([]);
        foreach ($values as $name => $value) {
            $this->{$name} = $value;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAs(string $name, FieldGetMode $mode = null): mixed
    {
        if ($this->_attributes->has($name)) {
            return $this->_attributes->get($name)->get($mode ?? static::DEFAULT_GET_MODE);
        } elseif ($this->_rawAttributes->has($name)) {
            $this->set($name, $this->_rawAttributes->get($name));
            return $this->_attributes->get($name)->get($mode ?? static::DEFAULT_GET_MODE);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function only(?array $names = null, ?FieldGetMode $mode = null): Collection
    {
        $names ??= array_merge(
            $this->_attributes->keys()->toArray(),
            $this->_rawAttributes->keys()->toArray()
        );
        $collection = collect();
        foreach ($names as $name) {
            $collection->put($name, $this->getAs($name, $mode));
        }
        return $collection;
    }

    /**
     * @inheritdoc
     */
    public function except(?array $names = [], ?FieldGetMode $mode = null): Collection
    {
        $keys ??= array_merge(
            $this->_attributes->keys()->toArray(),
            $this->_rawAttributes->keys()->toArray()
        );
        $collection = collect();
        foreach ($keys as $key) {
            if (in_array($key, $names)) {
                continue;
            }
            $collection->put($key, $this->getAs($key, $mode));
        }
        return $collection;
    }

    /**
     * Gets readonly field keys
     *
     * @return array
     */
    protected static function getReadonlyFields(): array
    {
        return static::$_readonlyFields ??= static::fields()->filter(function ($settings) {
            return $settings->readonly();
        })->keys()->toArray();
    }

    /**
     * Gets fields which were updated
     * Ignores readonly fields
     *
     * @param FieldGetMode|null $mode
     * @return Collection
     */
    protected function getFieldUpdates(?FieldGetMode $mode = null): Collection
    {
        return $this->only($this->updatedFields, $mode)
            ->except(static::getReadonlyFields());
    }

    /**
     * Gets field value. Null on undefined
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->getAs($name);
    }

    /**
     * Checks if property exists
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->_rawAttributes->has($name)
            || $this->_attributes->has($name);
    }

    /**
     * Sets value without adding to updated fields
     *
     * @param string $name Field name
     * @param mixed $value Value
     */
    protected function set(string $name, mixed $value): void
    {
        if ($this->_attributes->has($name)) {
            $this->_attributes->get($name)->set($value);
        } elseif (static::fields()->has($name)) {
            $this->_attributes->put($name, static::fields()->get($name)->field($value));
        } else {
            $this->_attributes->put($name, new DefaultField(
                $value,
                new Settings($name, FieldType::Cast, (
                    is_array($value)
                    || is_a($value, Collection::class, true)
                )))
            );
        }
    }

    /**
     * Sets field value. Default field on undefined (FieldType::Cast).
     *
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
        if (!in_array($name, $this->updatedFields)) {
            $this->updatedFields[] = $name;
        }
    }

    /**
     * @inheritdoc
     */
    public function toArray(?FieldGetMode $mode = null): array
    {
        return $this->only(null, $mode)->toArray();
    }

    /**
     * @inheritdoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->{$offset});
    }

    /**
     * @inheritdoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    /**
     * @inheritdoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->_attributes->offsetUnset($offset);
        $this->_rawAttributes->offsetUnset($offset);
    }

    /**
     * @inheritdoc
     */
    public function collect(array|Collection $collection, ?ApiClientInterface $client = null): ModelCollection
    {
        return ModelCollection::make($collection)->map(
            fn($attributes) => static::make($attributes, $client ?? $this->getClient())
        );
    }

    /**
     * @inheritdoc
     */
    public function setClient(?ApiClientInterface $client): static
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getClient(): ?ApiClientInterface
    {
        return $this->client ??= static::$defaultClient ?? null;
    }

    /**
     * @inheritdoc
     */
    #[NoReturn]
    public function dd(...$args): void
    {
        dd($this->toArray(), ...$args);
    }

    /**
     * @inheritdoc
     */
    #[NoReturn]
    public function dump(...$args): static
    {
        dump($this->toArray(), ...$args);
        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        return match ($name) {
            'withClient' => $this->setClient(...$arguments),
            default => null
        };
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return match ($name) {
            'withClient' => static::make()->setClient(...$arguments),
            default => null
        };
    }
}