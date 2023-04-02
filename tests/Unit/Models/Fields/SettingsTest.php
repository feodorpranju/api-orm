<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Models\Fields\DateTimeField;
use Feodorpranju\ApiOrm\Models\Fields\FloatField;
use Feodorpranju\ApiOrm\Models\Fields\IntField;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Models\Fields\StringField;
use Generator;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     * @param mixed $value
     * @param FieldType $type
     * @param bool $multiple
     * @param string $class
     * @throws \Feodorpranju\ApiOrm\Exceptions\Fields\UndefinedFieldTypeException
     */
    public function testField(mixed $value, FieldType $type, bool $multiple, string $class) {
        $settings = new Settings($type->value, $type, $multiple);
        $this->assertInstanceOf($class, $settings->field($value));
    }

    public static function valueDataProvider(): Generator
    {
        //single
        yield "single_int" => [
            1,
            FieldType::Int,
            false,
            IntField::class
        ];

        yield "single_float" => [
            1.1,
            FieldType::Float,
            false,
            FloatField::class
        ];

        yield "single_string" => [
            "Lorem ipsum",
            FieldType::String,
            false,
            StringField::class
        ];

        yield "single_datetime" => [
            "2023-01-01 13:15:12",
            FieldType::Datetime,
            false,
            DateTimeField::class
        ];

        yield "single_date" => [
            "2023-01-01",
            FieldType::Date,
            false,
            DateTimeField::class
        ];

        yield "single_time" => [
            "13:15:12",
            FieldType::Time,
            false,
            DateTimeField::class
        ];

        //multiple
        yield "multiple_int" => [
            [1, 3],
            FieldType::Int,
            true,
            IntField::class
        ];

        yield "multiple_float" => [
            [1.1, 17.3],
            FieldType::Float,
            true,
            FloatField::class
        ];

        yield "multiple_string" => [
            ["Lorem", "ipsum"],
            FieldType::String,
            true,
            StringField::class
        ];

        yield "multiple_datetime" => [
            ["2023-01-01 13:15:12", "2024-02-02 17:14:13"],
            FieldType::Datetime,
            true,
            DateTimeField::class
        ];

        yield "multiple_date" => [
            ["2023-01-01", "2024-02-02"],
            FieldType::Date,
            true,
            DateTimeField::class
        ];

        yield "multiple_time" => [
            ["13:15:12", "17:14:13"],
            FieldType::Time,
            true,
            DateTimeField::class
        ];
    }
}
