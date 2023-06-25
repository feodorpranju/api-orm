<?php

namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;

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
        $result = "test@test.test";

        $values = [
            "valid_single_email" => ['test@test.test', $result, $result, $result],
            "valid_single_email_with_spaces" => [" test@test.test ", $result, $result, $result],
            "valid_single_email_null" => [null, '', null, null],
            "valid_single_email_empty" => ['', '', null, null],
            "valid_single_email_empty_with_space" => [' ', '', null, null],
        ];

        foreach ($values as $name => $v) {
            yield $name => [
                $v[0],
                false,
                $v[1],
                $v[2],
                $v[3],
            ];
        }

        yield "valid_multiple_email" => [
            collect($values)->map(fn($v) => $v[0])->values(),
            true,
            collect($values)->map(fn($v) => $v[1])->values(),
            collect($values)->map(fn($v) => $v[2])->values(),
            collect($values)->map(fn($v) => $v[3])->values(),
        ];
    }
}
