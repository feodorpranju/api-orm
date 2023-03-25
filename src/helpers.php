<?php

use Illuminate\Config\Repository;

if (! function_exists('config_path')) {
    $GLOBALS["feodorpranju__config_path"] = dirname(__DIR__)."/config/";
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = ''): string
    {
        if (empty($path)) {
            return $GLOBALS["feodorpranju__config_path"];
        }
        return $GLOBALS["feodorpranju__config_path"] = $path;
    }
}

if (!function_exists("config")) {
    $GLOBALS["feodorpranju__config_array"] = [];
    foreach (scandir(config_path()) as $path) {
        if (in_array($path, [".", ".."])) {
            continue;
        }
        $GLOBALS["feodorpranju__config_array"][basename($path, ".php")] = include config_path().$path;
    }
    $GLOBALS["feodorpranju__config_repository"] = new Repository($GLOBALS["feodorpranju__config_array"]);
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|Repository
     */
    function config(array|string|null $key = null, mixed $default = null): mixed
    {
        /**
         * @var Repository $repository
         */
        $repository =  $GLOBALS["feodorpranju__config_repository"];

        if ($key === null) {
            return $repository;
        }

        if (is_array($key)) {
            $repository->set($key);
            return null;
        }

        return $repository->get($key, $default);
    }
}