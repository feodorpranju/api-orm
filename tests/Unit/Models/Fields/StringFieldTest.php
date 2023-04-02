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

class StringFieldTest extends TestCase
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
        $field = $settings->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String));
        $this->assertEquals($api, $field->get(FieldGetMode::Api));
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable));
    }

    public static function valueDataProvider(): Generator
    {
        //single
        //from string
        yield "valid_single_str_from_str" => [
            "Lorem ipsum",
            self::getSettings(false),
            "Lorem ipsum",
            "Lorem ipsum",
            "Lorem ipsum"
        ];

        //from int
        yield "valid_single_str_from_int" => [
            111,
            self::getSettings(false),
            "111",
            "111",
            "111"
        ];

        //from float
        yield "valid_single_str_from_float" => [
            111.1,
            self::getSettings(false),
            "111.1",
            "111.1",
            "111.1"
        ];

        //multiple
        //from string
        yield "valid_multiple_str_from_str" => [
            ["Lorem", "ipsum"],
            self::getSettings(true),
            collect(["Lorem", "ipsum"]),
            collect(["Lorem", "ipsum"]),
            collect(["Lorem", "ipsum"]),
        ];

        //from int
        yield "valid_multiple_str_from_int" => [
            [1, 2, 7],
            self::getSettings(true),
            collect(["1", "2", "7"]),
            collect(["1", "2", "7"]),
            collect(["1", "2", "7"]),
        ];

        //from float
        yield "valid_multiple_str_from_float" => [
            [1.3, 2.5, 7.8],
            self::getSettings(true),
            collect(["1.3", "2.5", "7.8"]),
            collect(["1.3", "2.5", "7.8"]),
            collect(["1.3", "2.5", "7.8"]),
        ];
    }

    public static function getSettings(bool $multiple): FieldSettings
    {
        return new Settings("string", FieldType::String, $multiple);
    }
}
