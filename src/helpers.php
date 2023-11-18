<?php

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

if (!function_exists("collect")) {
    function collect(mixed $value = []): Collection
    {
        return new Collection($value);
    }
}

$container = new Container();

Facade::setFacadeApplication($container);