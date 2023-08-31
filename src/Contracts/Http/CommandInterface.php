<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


interface CommandInterface
{
    /**
     * @return ResponseInterface
     */
    public function call(): ResponseInterface;
}