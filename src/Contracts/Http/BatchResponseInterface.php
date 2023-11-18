<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


interface BatchResponseInterface extends ResponseInterface
{
    /**
     * @return ResponseInterface[]
     */
    public function getResults(): array;
}