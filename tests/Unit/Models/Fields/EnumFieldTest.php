<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

use Feodorpranju\ApiOrm\Exceptions\Fields\InvalidValueTypeException;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Generator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class EnumFieldTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     * @param mixed $value
     * @param array|Collection $items
     * @param bool $multiple
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     * @throws InvalidValueTypeException
     */
    public function testGet(mixed $value, array|Collection $items, bool $multiple, mixed $string, mixed $api, mixed $usable)
    {
        $field = (new Settings("enum", FieldType::Enum, $multiple))
            ->setItems($items)->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String), "String");
        $this->assertEquals($api, $field->get(FieldGetMode::Api), "Api");
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable), "Usable");
    }

    public static function valueDataProvider(): Generator
    {
        $items = [
            653 => "Lorem",
            75 => "ipsum",
            24 => 143,
        ];

        $values = [
            "valid_single_enum_string_from_id_int" => [653, "Lorem", 653, "Lorem"],
            "valid_single_enum_int_from_id_str" => ["24", 143, 24, 143],
            "valid_single_enum_string_from_value_str" => ["Lorem", "Lorem", 653, "Lorem"],
            "valid_single_enum_int_from_value_int" => [143, 143, 24, 143],
        ];

        foreach ($values as $name => $value) {
            yield $name => [
                $value[0],
                $items,
                false,
                $value[1],
                $value[2],
                $value[3]
            ];
        }


        $case = [];
        foreach ($values as $value) {
            foreach ($value as $idx => $caseItem) {
                $case[$idx] ??= collect([]);
                $case[$idx]->push($caseItem);
            }
        }

        yield "valid_multiple_enum_mixed" => [
            $case[0],
            $items,
            true,
            $case[1],
            $case[2],
            $case[3]
        ];
    }
}
