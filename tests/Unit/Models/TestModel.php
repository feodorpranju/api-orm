<?php


namespace Feodorpranju\ApiOrm\Tests\Unit\Models;


use Feodorpranju\ApiOrm\Models\AbstractModel;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Illuminate\Support\Collection;

class TestModel extends AbstractModel
{
    public static function fields(): Collection
    {
        return collect([
            "single_string" => new Settings("single_string", FieldType::String),
            "single_int" => new Settings("single_int", FieldType::Int),
            "single_float" => new Settings("single_float", FieldType::Float),
            "single_datetime" => new Settings("single_datetime", FieldType::Datetime),
            "single_date" => new Settings("single_date", FieldType::Date),
            "single_time" => new Settings("single_time", FieldType::Time),
            "single_phone" => new Settings("single_phone", FieldType::Phone),
            "single_email" => new Settings("single_email", FieldType::Email),
            "single_enumeration" => (new Settings("single_enumeration", FieldType::Enum))->setItems(self::enumItems()),
            "multiple_string" => new Settings("multiple_string", FieldType::String, true),
            "multiple_int" => new Settings("multiple_int", FieldType::Int, true),
            "multiple_float" => new Settings("multiple_float", FieldType::Float, true),
            "multiple_datetime" => new Settings("multiple_datetime", FieldType::Datetime, true),
            "multiple_date" => new Settings("multiple_date", FieldType::Date, true),
            "multiple_time" => new Settings("multiple_time", FieldType::Time, true),
            "multiple_phone" => new Settings("multiple_phone", FieldType::Phone, true),
            "multiple_email" => new Settings("multiple_email", FieldType::Email, true),
            "multiple_enumeration" => (new Settings("multiple_enumeration", FieldType::Enum, true))->setItems(self::enumItems()),
        ]);
    }

    public static function enumItems(): array
    {
        return [
            1 => "Lorem",
            2 => "ipsum",
            3 => 143
        ];
    }

    public static function find(
        array|Collection $conditions = [],
        string $orderBy = null,
        string $orderDirection = null,
        array $select = [],
        int $offset = 0,
        int $limit = 50
    ): Collection
    {
        $models = collect([]);
        $i = 0;
        while ($offset + $i < self::count($conditions) && $i++ < $limit) {
            $models->push(new self);
        }
        return $models;
    }

    public static function count(array|Collection $conditions): int
    {
        return (int)$conditions[0][2] ?? 0;
    }
}