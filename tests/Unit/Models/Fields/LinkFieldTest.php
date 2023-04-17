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
use Feodorpranju\ApiOrm\Tests\Unit\Models\TestModel;
use Generator;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class LinkFieldTest extends TestCase
{
    /**
     * @dataProvider valueDataProvider
     * @param mixed $value
     * @param bool $multiple
     * @param mixed $string
     * @param mixed $api
     * @param mixed $usable
     */
    public function testGet(mixed $value, bool $multiple) {
        $field = (new Settings("link", FieldType::Link, $multiple, false, TestModel::class))
            ->field($value);
        if ($multiple) {
            $this->assertContainsOnlyInstancesOf(TestModel::class, $field->get(FieldGetMode::Usable));
        } else {
            $this->assertInstanceOf(TestModel::class, $field->get(FieldGetMode::Usable));
        }
    }

    public static function valueDataProvider(): Generator
    {
        yield "valid_single_link" => [1, false];
        yield "valid_multiple_link" => [[1, 2], true];
    }
}
