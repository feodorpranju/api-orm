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
     * @param bool $multple
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     */
    public function testGet(mixed $value, bool $multple, mixed $string, mixed $api, mixed $usable) {
        $field = (new Settings("float", FieldType::Float, $multple))->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String), "String");
        $this->assertEquals($api, $field->get(FieldGetMode::Api), "Api");
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable), "Usable");
    }

    public static function valueDataProvider(): Generator
    {
        $values = [
            "valid_single_float_from_str" => ["1.1", "1.1", 1.1, 1.1],
            "valid_single_float_from_float" => [1.1, "1.1", 1.1, 1.1],
            "valid_single_float_from_int" => [1, "1", 1, 1],
            "valid_single_float_from_null" => [null, "", null, 0],
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

        yield "valid_multiple_float" => [
            array_map(function ($case) {return $case[0];}, array_values($values)),
            true,
            collect(array_map(function ($case) {return $case[1];}, array_values($values))),
            collect(array_map(function ($case) {return $case[2];}, array_values($values))),
            collect(array_map(function ($case) {return $case[3];}, array_values($values))),
        ];
    }
}
