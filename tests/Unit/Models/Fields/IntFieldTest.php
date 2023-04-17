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
    public function testGet(mixed $value, bool $multiple, mixed $string, mixed $api, mixed $usable) {
        $field = (new Settings("int", FieldType::Int, $multiple))->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String), "String");
        $this->assertEquals($api, $field->get(FieldGetMode::Api), "Api");
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable), "Usable");
    }

    public static function valueDataProvider(): Generator
    {
        $values = [
            "valid_single_int_from_str" => ["1", "1", 1, 1],
            "valid_single_int_from_float_int" => [1.0, "1", 1, 1],
            "valid_single_int_from_float_dot_above_half" => [1.7, "1", 1, 1],
            "valid_single_int_from_float_dot_half" => [1.5, "1", 1, 1],
            "valid_single_int_from_float_dot_under_half" => [1.4, "1", 1, 1],
            "valid_single_int_from_int" => [1, "1", 1, 1],
            "valid_single_int_from_null" => [null, "", null, 0],
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

        yield "valid_multiple_int" => [
            array_map(function ($case) {return $case[0];}, array_values($values)),
            true,
            collect(array_map(function ($case) {return $case[1];}, array_values($values))),
            collect(array_map(function ($case) {return $case[2];}, array_values($values))),
            collect(array_map(function ($case) {return $case[3];}, array_values($values))),
        ];
    }
}
