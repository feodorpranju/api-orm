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
    public function testGet(mixed $value, bool $multiple, mixed $string, mixed $api, mixed $usable) {
        $field = (new Settings("string", FieldType::String, $multiple))->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String));
        $this->assertEquals($api, $field->get(FieldGetMode::Api));
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable));
    }

    public static function valueDataProvider(): Generator
    {
        $values = [
            "valid_single_str_from_str" => ["Lorem ipsum", "Lorem ipsum", "Lorem ipsum", "Lorem ipsum"],
            "valid_single_str_from_float_int" => [1.0, "1", "1", "1"],
            "valid_single_str_from_float" => [1.7, "1.7", "1.7", "1.7"],
            "valid_single_str_from_int" => [1, "1", 1, 1],
            "valid_single_str_from_empty_str" => ["", "", "", ""],
            "valid_single_str_from_null" => [null, "", null, null],
        ];

        foreach ($values as $name => $value) {
            yield $name => [
                $value[0],
                false,
                $value[1],
                $value[2],
                $value[3]
            ];
        }

        yield "valid_multiple_str" => [
            array_map(function ($case) {return $case[0];}, array_values($values)),
            true,
            collect(array_map(function ($case) {return $case[1];}, array_values($values))),
            collect(array_map(function ($case) {return $case[2];}, array_values($values))),
            collect(array_map(function ($case) {return $case[3];}, array_values($values))),
        ];
    }
}
