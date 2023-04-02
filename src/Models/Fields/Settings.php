<?php


namespace Feodorpranju\ApiOrm\Models\Fields;


use Feodorpranju\ApiOrm\Contracts\FieldModel;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Exceptions\Fields\UndefinedFieldTypeException;

class Settings implements FieldSettings
{
    static string $config = "api-orm.fields";

    public function __construct(
        protected string|int $id,
        protected FieldType $type,
        protected bool $multiple = false,
        protected bool $readonly = false
    ){}

    public function multiple(): bool
    {
        return $this->multiple;
    }

    public function readonly(): bool
    {
        return $this->readonly;
    }

    public function type(): FieldType
    {
        return $this->type;
    }

    public function id(): string|int
    {
        return $this->id;
    }

    /**
     * Gets class for field type
     *
     * @param FieldType $type
     * @return string
     */
    protected static function getFieldClass(FieldType $type): string
    {
        if ($class = config(static::$config.".".$type->value)) {
            return $class;
        } else {
            return DefaultField::class;
        }
    }

    /**
     * @inheritdoc
     */
    public function field(mixed $value): FieldModel
    {
        $class = static::getFieldClass($this->type());
        return new $class($value, $this);
    }
}