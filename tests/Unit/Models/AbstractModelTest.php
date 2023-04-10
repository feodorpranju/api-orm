<?php


namespace Feodorpranju\ApiOrm\Tests\Unit\Models;


use Feodorpranju\ApiOrm\Models\Fields\DateTimeField;
use Feodorpranju\ApiOrm\Models\Fields\EmailField;
use Feodorpranju\ApiOrm\Models\Fields\EnumField;
use Feodorpranju\ApiOrm\Models\Fields\FloatField;
use Feodorpranju\ApiOrm\Models\Fields\IntField;
use Feodorpranju\ApiOrm\Models\Fields\PhoneField;
use Feodorpranju\ApiOrm\Models\Fields\StringField;
use Generator;
use PHPUnit\Framework\TestCase;

class AbstractModelTest extends TestCase
{
    /**
     * @param string $id
     * @param mixed $value
     * @param string $fieldClass
     * @dataProvider valueDataProvider
     */
    public function testField(string $id, mixed $value, string $fieldClass): void
    {
        $model = new TestModel([$id => $value]);
        $this->assertTrue(isset($model->{$id}), "Field '$id' isset");
        $this->assertInstanceOf($fieldClass, $model->fields()->get($id)->field($value), "Field '$id' class '$fieldClass'");
    }

    public static function valueDataProvider(): Generator
    {
        $values = [
            "single_string" => ["Lorem", StringField::class],
            "single_int" => [143, IntField::class],
            "single_float" => [14.3, FloatField::class],
            "single_datetime" => ["2023-01-01 15:14:16", DateTimeField::class],
            "single_date" => ["2023-01-01", DateTimeField::class],
            "single_time" => ["15:14:16", DateTimeField::class],
            "single_phone" => ["+79999999999", PhoneField::class],
            "single_email" => ["test@test.test", EmailField::class],
            "single_enumeration" => ["Lorem", EnumField::class],
            "multiple_string" => [["Lorem", "ipsum"], StringField::class],
            "multiple_int" => [[143, "167"], IntField::class],
            "multiple_float" => [[14.3, "15.4"], FloatField::class],
            "multiple_datetime" => [["2023-01-01 15:14:16", "2024-02-02 16:15:17"], DateTimeField::class],
            "multiple_date" => [["2023-01-01", "2024-02-02"], DateTimeField::class],
            "multiple_time" => [["15:14:16", "16:15:17"], DateTimeField::class],
            "multiple_phone" => [["+79999999999", "+78888888888"], PhoneField::class],
            "multiple_email" => [["test@test.test", "example@example.example"], EmailField::class],
            "multiple_enumeration" => [["Lorem", 2], EnumField::class],
        ];

        foreach ($values as $key => $value) {
            yield $key => array_merge([$key], $value);
        }
    }
}