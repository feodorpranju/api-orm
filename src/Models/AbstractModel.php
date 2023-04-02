<?php


namespace Feodorpranju\ApiOrm\Models;


use Feodorpranju\ApiOrm\Contracts\ModelInterface;
use Feodorpranju\ApiOrm\Contracts\QueryBuilderInterface;
use Feodorpranju\ApiOrm\Models\Fields\DefaultField;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Illuminate\Support\Collection;

abstract class AbstractModel implements ModelInterface
{
    private static string $_entity = "";
    protected Collection $_attributes;
    protected static Collection $_fields;

    public function __construct(array|Collection $attributes = [])
    {
        $this->setFields($attributes);
    }

    public static function get(int $id): ModelInterface
    {
        // TODO: Implement get() method.
    }

    public static function entity(): string
    {
        return static::$_entity;
    }

    public static function select(array $fields = null): QueryBuilderInterface
    {
        // TODO: Implement select() method.
    }

    public static function fields(): Collection
    {
        return static::$_fields ??= collect([]);
    }

    protected function setFields(Collection $values)
    {
        foreach ($values as $name => $value) {
            $this->{$name} = $value;
        }
    }

    /**
     * Gets field value. Null on undefined
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if ($this->_attributes->has($name)) {
            return $this->_attributes->get($name);
        }
        return null;
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