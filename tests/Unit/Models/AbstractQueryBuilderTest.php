<?php


namespace Feodorpranju\ApiOrm\Tests\Unit\Models;


use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractQueryBuilderTest
 * @package Feodorpranju\ApiOrm\Tests\Unit\Models
 *
 * @author feodorpranju
 * @tag query-builder
 */
class AbstractQueryBuilderTest extends TestCase
{
    /**
     * @param array $conditions
     * @param array $result
     * @dataProvider whereDataProvider
     */
    public function testWhere(array $conditions, array $result): void
    {
        $qb = TestModel::where(...$conditions);

        $this->assertEquals($result, $qb->conditions->toArray());
    }

    /**
     * @param array $conditions
     * @param int $count
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

    public static function whereDataProvider(): Generator
    {
        foreach (TestQueryBuilder::AVAILABLE_OPERANDS as $operand) {
            yield "single_operand_$operand" => [
                ['id', $operand, 'test'],
                [['id', $operand, 'test']],
            ];

            yield "multiple_operand_$operand" => [
                [[
                    ['id', $operand, 'Lorem'],
                    ['title', $operand, 'ipsum']
                ]],
                [
                    ['id', $operand, 'Lorem'],
                    ['title', $operand, 'ipsum']
                ],
            ];
        }

        yield "single_operand_undefined" => [
            ['id', '!!!', 'test'],
            [['id', TestQueryBuilder::ON_UNDEFINED_OPERAND, 'test']],
        ];

        yield "single_operand_default" => [
            ['id', 'test'],
            [['id', TestQueryBuilder::DEFAULT_OPERAND, 'test']],
        ];

        yield "multiple_operand_default_undefined" => [
            [[
                ['id', 'Lorem'],
                ['title', '!!!', 'ipsum']
            ]],
            [
                ['id', TestQueryBuilder::DEFAULT_OPERAND, 'Lorem'],
                ['title', TestQueryBuilder::ON_UNDEFINED_OPERAND, 'ipsum']
            ],
        ];

        yield "multiple_operand_with_keys" => [
            [[
                'id' => 'Lorem',
                'title' => 'ipsum'
            ]],
            [
                ['id', TestQueryBuilder::DEFAULT_OPERAND, 'Lorem'],
                ['title', TestQueryBuilder::DEFAULT_OPERAND, 'ipsum']
            ],
        ];

        yield "multiple_operand_with_keys_mixed" => [
            [[
                'id' => 'Lorem',
                'title' => 'ipsum'
            ]],
            [
                ['id', TestQueryBuilder::DEFAULT_OPERAND, 'Lorem'],
                ['title', TestQueryBuilder::DEFAULT_OPERAND, 'ipsum']
            ],
        ];

        yield "multiple_operand_mixed" => [
            [[
                ['id', 'Lorem'],
                'title' => 'ipsum',
                'name' => ['!=', 'dolor'],
            ]],
            [
                ['id', TestQueryBuilder::DEFAULT_OPERAND, 'Lorem'],
                ['title', TestQueryBuilder::DEFAULT_OPERAND, 'ipsum'],
                ['name', '!=', 'dolor'],
            ],
        ];
    }
}