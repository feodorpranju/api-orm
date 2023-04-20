<?php

use Illuminate\Support\Collection;

if (!function_exists("collect")) {
    function collect(mixed $value = []): Collection
    {
        return new Collection($value);
    }
}