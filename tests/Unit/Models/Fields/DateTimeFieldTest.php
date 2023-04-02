<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

use Carbon\Carbon;
use Feodorpranju\ApiOrm\Contracts\FieldSettings;
use Feodorpranju\ApiOrm\Models\Fields\DateTimeField;
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
     * @param FieldSettings $settings
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     */
    public function testGet(mixed $value, FieldSettings $settings, mixed $string, mixed $api, mixed $usable) {
        $field = new DateTimeField($value, $settings);
        $this->assertEquals($string, $field->get(FieldGetMode::String));
        $this->assertEquals($api, $field->get(FieldGetMode::Api));
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable));
    }

    public static function valueDataProvider(): Generator
    {
        $datetime1 = new Carbon("2023-01-01 13:15:12");
        $datetime2 = new Carbon("2024-02-02 14:16:13");
        $date1 = new Carbon($datetime1->format(self::$apiFormats["date"]));
        $date2 = new Carbon($datetime2->format(self::$apiFormats["date"]));
        $time1 = new Carbon($datetime1->format(self::$apiFormats["time"]));
        $time2 = new Carbon($datetime1->format(self::$apiFormats["time"]));

        //single
        //datetime
        yield "valid_single_datetime_from_str" => [
            $datetime1->format(self::$apiFormats["datetime"]),
            self::getSettings(FieldType::Datetime, false),
            $datetime1->format(self::$stringFormats["datetime"]),
            $datetime1->format(self::$apiFormats["datetime"]),
            $datetime1
        ];

        yield "valid_single_datetime_from_carbon" => [
            $datetime1,
            self::getSettings(FieldType::Datetime, false),
            $datetime1->format(self::$stringFormats["datetime"]),
            $datetime1->format(self::$apiFormats["datetime"]),
            $datetime1
        ];

        yield "valid_single_datetime_from_datetime" => [
            $datetime1->toDateTime(),
            self::getSettings(FieldType::Datetime, false),
            $datetime1->format(self::$stringFormats["datetime"]),
            $datetime1->format(self::$apiFormats["datetime"]),
            $datetime1
        ];

        //date
        yield "valid_single_date_from_str" => [
            $date1->format(self::$apiFormats["date"]),
            self::getSettings(FieldType::Date, false),
            $date1->format(self::$stringFormats["date"]),
            $date1->format(self::$apiFormats["date"]),
            $date1
        ];

        yield "valid_single_date_from_carbon" => [
            $date1,
            self::getSettings(FieldType::Date, false),
            $date1->format(self::$stringFormats["date"]),
            $date1->format(self::$apiFormats["date"]),
            $date1
        ];

        yield "valid_single_date_from_datetime" => [
            $date1->toDateTime(),
            self::getSettings(FieldType::Date, false),
            $date1->format(self::$stringFormats["date"]),
            $date1->format(self::$apiFormats["date"]),
            $date1
        ];

        //time
        yield "valid_single_time_from_str" => [
            $time1->format(self::$apiFormats["time"]),
            self::getSettings(FieldType::Time, false),
            $time1->format(self::$stringFormats["time"]),
            $time1->format(self::$apiFormats["time"]),
            $time1
        ];

        yield "valid_single_time_from_carbon" => [
            $time1,
            self::getSettings(FieldType::Time, false),
            $time1->format(self::$stringFormats["time"]),
            $time1->format(self::$apiFormats["time"]),
            $time1
        ];

        yield "valid_single_time_from_datetime" => [
            $time1->toDateTime(),
            self::getSettings(FieldType::Time, false),
            $time1->format(self::$stringFormats["time"]),
            $time1->format(self::$apiFormats["time"]),
            $time1
        ];

        //multiple
        //datetime
        yield "valid_multiple_datetime_from_str" => [
            [
                $datetime1->format(self::$apiFormats["datetime"]),
                $datetime2->format(self::$apiFormats["datetime"])
            ],
            self::getSettings(FieldType::Datetime, true),
            collect([
                $datetime1->format(self::$stringFormats["datetime"]),
                $datetime2->format(self::$stringFormats["datetime"])
            ]),
            collect([
                $datetime1->format(self::$apiFormats["datetime"]),
                $datetime2->format(self::$apiFormats["datetime"])
            ]),
            collect([$datetime1, $datetime2])
        ];

        yield "valid_multiple_datetime_from_carbon" => [
            [$datetime1, $datetime2],
            self::getSettings(FieldType::Datetime, true),
            collect([
                $datetime1->format(self::$stringFormats["datetime"]),
                $datetime2->format(self::$stringFormats["datetime"])
            ]),
            collect([
                $datetime1->format(self::$apiFormats["datetime"]),
                $datetime2->format(self::$apiFormats["datetime"])
            ]),
            collect([$datetime1, $datetime2])
        ];

        yield "valid_multiple_datetime_from_datetime" => [
            [$datetime1->toDateTime(), $datetime2->toDateTime()],
            self::getSettings(FieldType::Datetime, true),
            collect([
                $datetime1->format(self::$stringFormats["datetime"]),
                $datetime2->format(self::$stringFormats["datetime"])
            ]),
            collect([
                $datetime1->format(self::$apiFormats["datetime"]),
                $datetime2->format(self::$apiFormats["datetime"])
            ]),
            collect([$datetime1, $datetime2])
        ];

        //date
        yield "valid_multiple_date_from_str" => [
            [
                $date1->format(self::$apiFormats["date"]),
                $date2->format(self::$apiFormats["date"])
            ],
            self::getSettings(FieldType::Date, true),
            collect([
                $date1->format(self::$stringFormats["date"]),
                $date2->format(self::$stringFormats["date"])
            ]),
            collect([
                $date1->format(self::$apiFormats["date"]),
                $date2->format(self::$apiFormats["date"])
            ]),
            collect([$date1, $date2])
        ];

        yield "valid_multiple_date_from_carbon" => [
            [$date1, $date2],
            self::getSettings(FieldType::Date, true),
            collect([
                $date1->format(self::$stringFormats["date"]),
                $date2->format(self::$stringFormats["date"])
            ]),
            collect([
                $date1->format(self::$apiFormats["date"]),
                $date2->format(self::$apiFormats["date"])
            ]),
            collect([$date1, $date2])
        ];

        yield "valid_multiple_date_from_datetime" => [
            [$date1->toDateTime(), $date2->toDateTime()],
            self::getSettings(FieldType::Date, true),
            collect([
                $date1->format(self::$stringFormats["date"]),
                $date2->format(self::$stringFormats["date"])
            ]),
            collect([
                $date1->format(self::$apiFormats["date"]),
                $date2->format(self::$apiFormats["date"])
            ]),
            collect([$date1, $date2])
        ];

        //time
        yield "valid_multiple_time_from_str" => [
            [
                $time1->format(self::$apiFormats["time"]),
                $time2->format(self::$apiFormats["time"])
            ],
            self::getSettings(FieldType::Time, true),
            collect([
                $time1->format(self::$stringFormats["time"]),
                $time2->format(self::$stringFormats["time"])
            ]),
            collect([
                $time1->format(self::$apiFormats["time"]),
                $time2->format(self::$apiFormats["time"])
            ]),
            collect([$time1, $time2])
        ];

        yield "valid_multiple_time_from_carbon" => [
            [$time1, $time2],
            self::getSettings(FieldType::Time, true),
            collect([
                $time1->format(self::$stringFormats["time"]),
                $time2->format(self::$stringFormats["time"])
            ]),
            collect([
                $time1->format(self::$apiFormats["time"]),
                $time2->format(self::$apiFormats["time"])
            ]),
            collect([$time1, $time2])
        ];

        yield "valid_multiple_time_from_datetime" => [
            [$time1->toDateTime(), $time2->toDateTime()],
            self::getSettings(FieldType::Time, true),
            collect([
                $time1->format(self::$stringFormats["time"]),
                $time2->format(self::$stringFormats["time"])
            ]),
            collect([
                $time1->format(self::$apiFormats["time"]),
                $time2->format(self::$apiFormats["time"])
            ]),
            collect([$time1, $time2])
        ];
    }

    public static function getSettings(FieldType $type, bool $multiple): FieldSettings
    {
        return match($type) {
            FieldType::Datetime => new Settings($type->value, $type, $multiple),
            FieldType::Date => new Settings($type->value, $type, $multiple),
            FieldType::Time => new Settings($type->value, $type, $multiple),
        };
    }
}
