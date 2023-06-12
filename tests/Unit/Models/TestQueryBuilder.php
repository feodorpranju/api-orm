<?php


namespace Feodorpranju\ApiOrm\Tests\Unit\Models;


use Feodorpranju\ApiOrm\Models\AbstractQueryBuilder;

class TestQueryBuilder extends AbstractQueryBuilder
{
    protected const GENERATOR_LOOP_LIMIT = 200;

    public function __get(string $name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }
        return null;
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }
        return null;
    }
}