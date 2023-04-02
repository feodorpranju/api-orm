<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

use Carbon\Carbon;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Models\Fields\DateTimeField;
use Feodorpranju\ApiOrm\Models\Fields\IntField;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Generator;
use PHPUnit\Framework\TestCase;

class IntFieldTest extends TestCase
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
        $field = new IntField($value, $settings);
        $this->assertEquals($string, $field->get(FieldGetMode::String));
        $this->assertEquals($api, $field->get(FieldGetMode::Api));
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable));
    }

    public static function valueDataProvider(): Generator
    {
        //single
        //from string
        yield "valid_single_int_from_str" => [
            "1",
            self::getSettings(false),
            "1",
            1,
            1
        ];

        //from int
        yield "valid_single_int_from_int" => [
            1,
            self::getSettings(false),
            "1",
            1,
            1
        ];

        //multiple
        //from string
        yield "valid_multiple_int_from_str" => [
            ["1", "2", "7"],
            self::getSettings(true),
            collect(["1", "2", "7"]),
            collect([1, 2, 7]),
            collect([1, 2, 7]),
        ];

        //from int
        yield "valid_multiple_int_from_int" => [
            [1, 2, 7],
            self::getSettings(true),
            collect(["1", "2", "7"]),
            collect([1, 2, 7]),
            collect([1, 2, 7]),
        ];
    }

    public static function getSettings(bool $multiple): FieldSettings
    {
        return new Settings("int", FieldType::Int, $multiple);
    }
}
