<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


interface BatchInterface extends CommandInterface
{
    public function __construct(mixed $items = [], ApiClientInterface $apiClient = null);

    public function call(): BatchResponseInterface;
}