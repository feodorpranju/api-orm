<?php


namespace Feodorpranju\ApiOrm\Tests\Unit\Models\Fields;


use Feodorpranju\ApiOrm\Models\AbstractQueryBuilder;

class TestQueryBuilder extends AbstractQueryBuilder
{
    protected const generatorLoopLimit = 200;
}