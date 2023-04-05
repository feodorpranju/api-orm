<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

use Carbon\Carbon;
use Feodorpranju\ApiOrm\Models\Fields\Settings;
use Feodorpranju\ApiOrm\Enumerations\FieldType;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;
use Generator;
use PHPUnit\Framework\TestCase;

class DateTimeFieldTest extends TestCase
{
    protected static array $stringFormats = [
        "datetime" => "d.m.Y H:i:s",
        "date" => "d.m.Y",
        "time" => "H:i:s",
    ];

    protected static array $apiFormats = [
        "datetime" => "Y-m-d H:i:s",
        "date" => "Y-m-d",
        "time" => "H:i:s",
    ];

    /**
     * @dataProvider valueDataProvider
     * @param mixed $value
     * @param FieldType $type
     * @param bool $multiple
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     */
    public function testGet(mixed $value, FieldType $type, bool $multiple, mixed $string, mixed $api, mixed $usable) {
        $field = (new Settings($type->value, $type, $multiple))->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String), "String");
        $this->assertEquals($api, $field->get(FieldGetMode::Api), "Api");
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable), "Usable");
    }

    public static function valueDataProvider(): Generator
    {
        $datetime = new Carbon("2023-01-01 13:15:12");

        $types = [
            FieldType::Datetime,
            FieldType::Date,
            FieldType::Time,
        ];

        $formats = [
            "Y-m-d H:i:s" => $datetime,
            "d.m.Y H:i:s" => $datetime,
            "Y-m-d" => new Carbon($datetime->format(static::$stringFormats["date"])),
            "d.m.Y" => new Carbon($datetime->format(static::$stringFormats["date"])),
            "H:i:s" => new Carbon($datetime->format(static::$stringFormats["time"])),
            "Y-m-d\TH:i:s.000\Z" => $datetime,
//            "U" => $datetime,
            "c" => $datetime,
        ];

        foreach ($types as $type) {
            foreach ($formats as $format => $carbon) {
                yield "valid_single_{$type->value}_from_$format" => [
                    $datetime->format($format),
                    $type,
                    false,
                    $carbon->format(self::$stringFormats[$type->value]),
                    $carbon->format(self::$apiFormats[$type->value]),
                    $carbon
                ];
            }

            yield "valid_single_{$type->value}_from_carbon" => [
                $datetime,
                $type,
                false,
                $datetime->format(self::$stringFormats[$type->value]),
                $datetime->format(self::$apiFormats[$type->value]),
                $datetime
            ];

            yield "valid_single_{$type->value}_from_datetime" => [
                $datetime->toDateTime(),
                $type,
                false,
                $datetime->format(self::$stringFormats[$type->value]),
                $datetime->format(self::$apiFormats[$type->value]),
                $datetime
            ];
        }

        $valueCallback = fn($format): string => $datetime->format($format);
        $values = array_map($valueCallback, array_keys($formats));
        $values[] = $datetime->toDateTime();
        $values[] = $datetime->toDateTime();
        foreach ($types as $type) {
            $strCallback = fn($value): string => $value->format(self::$stringFormats[$type->value]);
            $stringResults = array_map($strCallback, array_values($formats));
            $stringResults[] = $datetime->format(self::$stringFormats[$type->value]);
            $stringResults[] = $datetime->format(self::$stringFormats[$type->value]);

            $apiCallback = fn($value): string => $value->format(self::$apiFormats[$type->value]);
            $apiResults = array_map($apiCallback, array_values($formats));
            $apiResults[] = $datetime->format(self::$apiFormats[$type->value]);
            $apiResults[] = $datetime->format(self::$apiFormats[$type->value]);

            $usableResults = array_map(function ($value) {
                return $value;
            }, array_values($formats));
            $usableResults[] = $datetime;
            $usableResults[] = $datetime;

            foreach ($formats as $format => $carbon) {
                yield "valid_multiple_{$type->value}_from_$format" => [
                    $values,
                    $type,
                    true,
                    collect($stringResults),
                    collect($apiResults),
                    collect($usableResults),
                ];
            }
        }
    }
}
