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

class EmailFieldTest extends TestCase
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
        $field = (new Settings("email", FieldType::Email, $multiple))->field($value);
        $this->assertEquals($string, $field->get(FieldGetMode::String));
        $this->assertEquals($api, $field->get(FieldGetMode::Api));
        $this->assertEquals($usable, $field->get(FieldGetMode::Usable));
    }

    public static function valueDataProvider(): Generator
    {
        $values = [
            "valid_single_email" => 'test@test.test',
            "valid_single_email_with_spaces" => " test@test.test ",
        ];

        $result = "test@test.test";

        foreach ($values as $name => $value) {
            yield $name => [
                $value,
                false,
                $result,
                $result,
                $result
            ];
        }

        $resultCollection = collect(array_fill(0, count($values), $result));
        yield "valid_multiple_email" => [
            array_values($values),
            true,
            $resultCollection,
            $resultCollection,
            $resultCollection
        ];
    }
}
