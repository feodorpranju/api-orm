<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

use Brick\PhoneNumber\PhoneNumberFormat;
use Carbon\Carbon;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Models\Fields\DateTimeField;
use Feodorpranju\ApiOrm\Models\Fields\IntField;
use Feodorpranju\ApiOrm\Models\Fields\PhoneField;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Generator;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class BoolFieldTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     * @param mixed $value
     * @param bool $multiple
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     */
    public function testGet(mixed $value, bool $multiple, mixed $string, mixed $api, mixed $usable) {
        $field = (new Settings("bool", FieldType::Bool, $multiple))->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String), "String");
        $this->assertEquals($api, $field->get(FieldGetMode::Api), "Api");
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable), "Usable");
    }

    public static function valueDataProvider(): Generator
    {
        $cases = [
            "valid_single_bool_true_from_" => [true, [true, 1, "1", "ok", "true", "yes", "y", "valid", "success"]],
            "valid_single_bool_false_from_" => [false, [false, 0, "0", "fail", "false", "no", "n", "invalid", "error"]],
        ];

        foreach ($cases as $name => $case) {
            foreach ($case[1] as $value) {
                yield $name.gettype($value)."_".(string)$value => [
                    $value,
                    false,
                    $case[0] ? "1" : "0",
                    $case[0],
                    $case[0],
                ];
            }

            yield Str::replace("single", "multiple", $name). "_".($case[0] ? "true" : "false") => [
                $case[1],
                true,
                collect(array_fill(0, count($case[1]), $case[0] ? "1" : "0")),
                collect(array_fill(0, count($case[1]), $case[0])),
                collect(array_fill(0, count($case[1]), $case[0])),
            ];
        }
    }
}
