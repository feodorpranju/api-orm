<?php


namespace Feodorpranju\ApiOrm\Models\Fields;


use Feodorpranju\ApiOrm\Contracts\FieldModel;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Feodorpranju\ApiOrm\Models\Fields\Enumerations\Item;
use Illuminate\Support\Collection;

class Settings implements FieldSettings
{
    protected const FIELD_TYPES = [
        "datetime" => \Feodorpranju\ApiOrm\Models\Fields\DateTimeField::class,
        "date" => \Feodorpranju\ApiOrm\Models\Fields\DateTimeField::class,
        "time" => \Feodorpranju\ApiOrm\Models\Fields\DateTimeField::class,
        "string" => \Feodorpranju\ApiOrm\Models\Fields\StringField::class,
        "integer" => \Feodorpranju\ApiOrm\Models\Fields\IntField::class,
        "float" => \Feodorpranju\ApiOrm\Models\Fields\FloatField::class,
        "phone" => \Feodorpranju\ApiOrm\Models\Fields\PhoneField::class,
        "email" => \Feodorpranju\ApiOrm\Models\Fields\EmailField::class,
        "enumeration" => \Feodorpranju\ApiOrm\Models\Fields\EnumField::class,
        "boolean" => \Feodorpranju\ApiOrm\Models\Fields\BoolField::class,
        "link" => \Feodorpranju\ApiOrm\Models\Fields\LinkField::class,
        "default" => \Feodorpranju\ApiOrm\Models\Fields\DefaultField::class,
    ];

    /**
     * Items for enumeration
     *
     * @var Collection
     * @see Item
     */
    protected Collection $items;

    public function __construct(
        protected mixed $id,
        protected ?FieldType $type = null,
        protected bool $multiple = false,
        protected bool $readonly = false,
        protected ?string $model = null
    ){}

    /**
     * @inheritdoc
     * @throws InvalidValueTypeException
     */
    public function setItems(array|Collection $items): static
    {
        $tItems = collect([]);

        foreach ($items as $key => $item) {
            if (is_a($item, Item::class, true)) {
                $tItems->push($item);
            } elseif (
                is_a($item, Collection::class, true)
                || is_array($item)
            ) {
                $tItems->push(new Item($item));
            } elseif (
                is_string($item)
                || is_int($item)
                || is_float($item)
            ) {
                $tItems->push(new Item(["id" => $key, "value" => $item]));
            } else {
                throw new InvalidValueTypeException(
                    "Enumeration item with key '$key' must be of type ".
                    "Item|Collection|array for field ".$this->id()
                );
            }
        }

        $this->items = $tItems;
        return $this;
    }

    public function items(): Collection
    {
        return match($this->type()) {
            FieldType::Enum => $this->items ?? collect([]),
            default => collect([])
        };
    }

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
        return static::FIELD_TYPES[$type->value]
            ?? self::FIELD_TYPES[$type->value]
            ?? DefaultField::class;
    }

    /**
     * @inheritdoc
     */
    public function field(mixed $value): FieldModel
    {
        $class = static::getFieldClass($this->type());
        return new $class($value, $this);
    }

    public function model(): ?string
    {
        return $this->model;
    }
}