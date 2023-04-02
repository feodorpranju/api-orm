<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

use Carbon\Carbon;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Models\Fields\DateTimeField;
use Feodorpranju\ApiOrm\Models\Fields\FloatField;
use Feodorpranju\ApiOrm\Models\Fields\IntField;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Generator;
use PHPUnit\Framework\TestCase;

class FloatFieldTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     * @param mixed $value
     * @param FieldSettings $settings
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     */
    public function testGet(mixed $value, FieldSettings $settings, mixed $string, mixed $api, mixed $usable) {
        $field = new FloatField($value, $settings);
        $this->assertEquals($string, $field->get(FieldGetMode::String));
        $this->assertEquals($api, $field->get(FieldGetMode::Api));
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable));
    }

    public static function valueDataProvider(): Generator
    {
        //single
        //from string
        yield "valid_single_float_from_str" => [
            "1.1",
            self::getSettings(false),
            "1.1",
            1.1,
            1.1
        ];

        //from int
        yield "valid_single_float_from_int" => [
            1,
            self::getSettings(false),
            "1",
            1.0,
            1.0
        ];

        //from float
        yield "valid_single_float_from_float" => [
            1.1,
            self::getSettings(false),
            "1.1",
            1.1,
            1.1
        ];

        //multiple
        //from string
        yield "valid_multiple_float_from_str" => [
            ["1.2", "2.3", "7.6"],
            self::getSettings(true),
            collect(["1.2", "2.3", "7.6"]),
            collect([1.2, 2.3, 7.6]),
            collect([1.2, 2.3, 7.6]),
        ];

        //from int
        yield "valid_multiple_int_from_int" => [
            [1, 2, 7],
            self::getSettings(true),
            collect(["1", "2", "7"]),
            collect([1, 2, 7]),
            collect([1, 2, 7]),
        ];

        //from float
        yield "valid_multiple_int_from_float" => [
            [1.2, 2.3, 7.6],
            self::getSettings(true),
            collect(["1.2", "2.3", "7.6"]),
            collect([1.2, 2.3, 7.6]),
            collect([1.2, 2.3, 7.6]),
        ];
    }

    public static function getSettings(bool $multiple): FieldSettings
    {
        return new Settings("int", FieldType::Float, $multiple);
    }
}
