<?php


namespace Feodorpranju\ApiOrm\Models;


use Feodorpranju\ApiOrm\Contracts\ModelInterface;
use Feodorpranju\ApiOrm\Contracts\QueryBuilderInterface;
use Feodorpranju\ApiOrm\Models\Fields\DefaultField;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Illuminate\Support\Collection;

abstract class AbstractModel implements ModelInterface
{
    protected static string $_entity = "";
    protected Collection $_attributes;
    protected Collection $_rawAttributes;
    protected static Collection $_fields;
    protected array $updatedFields = [];
    protected const setFieldsOnConstruct = false;
    protected const defaultGetMode = FieldGetMode::Usable;

    /**
     * @inheritdoc
     */
    public function __construct(array|Collection $attributes = [])
    {
        $this->_attributes = collect([]);
        if (static::setFieldsOnConstruct) {
            $this->setFields($attributes);
        }
        $this->_rawAttributes = collect($attributes);
    }

    /**
     * @inheritdoc
     */
    public static function get(int|string $id): static
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritdoc
     */
    public static function entity(): string
    {
        return static::$_entity;
    }

    /**
     * @inheritdoc
     */
    public static function select(array $fields = null): QueryBuilderInterface
    {
        // TODO: Implement select() method.
    }

    /**
     * @inheritdoc
     */
    public static function fields(): Collection
    {
        return static::$_fields ??= collect([]);
    }

    protected function setFields(array|Collection $values)
    {
        if (!isset($this->_attributes)) {
            $this->_attributes = collect([]);
        }
        foreach ($values as $name => $value) {
            $this->{$name} = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function getAs(string $name, FieldGetMode $mode = null): mixed
    {
        if ($this->_attributes->has($name)) {
            return $this->_attributes->get($name)->get($mode ?? static::defaultGetMode);
        } elseif ($this->_rawAttributes->has($name)) {
            $this->{$name} = $this->_rawAttributes->get($name);
            return $this->_attributes->get($name)->get($mode ?? static::defaultGetMode);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function only(?array $names = null, ?FieldGetMode $mode = null): Collection
    {
        $names ??= $this->_rawAttributes->keys();
        $collection = new Collection();
        foreach ($names as $name) {
            $collection->put($name, $this->getAs($name, $mode));
        }
        return $collection;
    }

    /**
     * @inheritdoc
     */
    public static function find(
        array|Collection $conditions = [],
        string $orderBy = null,
        string $orderDirection = null,
        array $select = [],
        int $offset = 0,
        int $limit = 50
    ): Collection
    {
        return collect([]);
    }

    /**
     * @inheritdoc
     */
    public static function count(array|Collection $conditions): int
    {
        return 0;
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

    public function __isset(string $name): bool
    {
        return $this->_rawAttributes->has($name)
            || $this->_attributes->has($name);
    }

    /**
     * Sets field value. Default field on undefined (FieldType::Cast).
     *
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value): void
    {
        if ($this->_attributes->has($name)) {
            $this->_attributes->get($name)->set($value);
            if (!in_array($name, $this->updatedFields)) {
                $this->updatedFields[] = $name;
            }
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
}