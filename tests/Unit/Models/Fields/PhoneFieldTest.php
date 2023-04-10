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
use PHPUnit\Framework\TestCase;

class PhoneFieldTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     * @param mixed $value
     * @param bool $multiple
     * @param int $format
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     */
    public function testGet(mixed $value, bool $multiple, int $format, mixed $string, mixed $api, mixed $usable) {
        PhoneField::$phoneNumberFormat = $format;
        $field = (new Settings("phone", FieldType::Phone, $multiple))->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String));
        $this->assertEquals($api, $field->get(FieldGetMode::Api));
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable));
    }

    public static function valueDataProvider(): Generator
    {
        $values = [
            "valid_single_phone_from_int" => 79999999999,
            "valid_single_phone_from_str" => "79999999999",
            "valid_single_phone_from_str_plus" => "+79999999999",
            "valid_single_phone_from_str_dashed" => "7 999 999-99-99",
            "valid_single_phone_from_str_brackets" => "7 (999) 999 99 99",
            "valid_single_phone_from_str_dashed_plus" => "+7 999 999-99-99",
            "valid_single_phone_from_str_brackets_plus" => "+7 (999) 999 99 99",
            "valid_single_phone_from_str_brackets_dashed" => "7 (999) 999-99-99",
            "valid_single_phone_from_str_brackets_dashed_plus" => "+7 (999) 999-99-99"
        ];

        $results = [
            PhoneNumberFormat::E164 => "+79999999999",
            PhoneNumberFormat::INTERNATIONAL => "+7 999 999-99-99",
            PhoneNumberFormat::NATIONAL => "8 (999) 999-99-99",
            PhoneNumberFormat::RFC3966 => "tel:+7-999-999-99-99",
        ];

        foreach ($results as $format => $result) {
            foreach ($values as $name => $value) {
                yield $name."_".$format => [
                    $value,
                    false,
                    $format,
                    $result,
                    $result,
                    $result
                ];
            }
        }

        foreach ($results as $format => $result) {
            $resultCollection = collect(array_fill(0, count($values), $result));
            yield "valid_multiple_phone_mixed_$format" => [
                array_values($values),
                true,
                $format,
                $resultCollection,
                $resultCollection,
                $resultCollection
            ];
        }
    }
}
