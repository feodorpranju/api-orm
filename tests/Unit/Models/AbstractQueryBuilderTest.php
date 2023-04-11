<?php


namespace Feodorpranju\ApiOrm\Tests\Unit\Models;


use Feodorpranju\ApiOrm\Exceptions\InvalidOperandException;
use Feodorpranju\ApiOrm\Tests\Unit\Models\Fields\TestQueryBuilder;
use Generator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class AbstractQueryBuilderTest extends TestCase
{
    /**
     * @param array $conditions
     * @param int $count
     * @throws InvalidOperandException
     * @dataProvider conditionDataProvider
     */
    public function testCount(array $conditions, int $count)
    {
        $builder = new TestQueryBuilder(TestModel::class);

        $this->assertEquals($count, TestModel::count([["","",$count]]), "Model count");
        $this->assertEquals($count, $builder->where(...$conditions)->count(), "Builder count");
    }

    /**
     * @param array $conditions
     * @param int $count
     * @param int $chunkSize
     * @dataProvider conditionDataProvider
     */
    public function testGet(array $conditions, int $count, int $chunkSize)
    {
        $builder = new TestQueryBuilder(TestModel::class);

        $this->assertEquals($count, $builder->where(...$conditions)->all()->count(), "All count");
        $this->assertEquals(
            $count < $chunkSize ? $count : $chunkSize,
            $builder->forPage(1, $chunkSize)->get()->count(),
            "Page count"
        );
    }

    public static function conditionDataProvider(): Generator
    {
        $counts = [10, 17, 48, 53, 74, 135, 150];
        $chunkSizes = [5, 10, 20, 35, 50, 100];

        foreach ($counts as $count) {
            foreach ($chunkSizes as $chunkSize) {
                yield "operator_count_$count"."_size_$chunkSize" => [
                    ["count", "=", $count],
                    $count,
                    $chunkSize
                ];

                yield "no_operator_count_$count"."_size_$chunkSize" => [
                    ["count", $count],
                    $count,
                    $chunkSize
                ];
            }
        }
    }
}