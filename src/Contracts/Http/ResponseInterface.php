<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


use \Symfony\Contracts\HttpClient\ResponseInterface as SymfonyResponseInterface;
use Throwable;


interface ResponseInterface
{
    public function __create(SymfonyResponseInterface $response);

    public static function make(SymfonyResponseInterface $response);

    public function getResult(): array;

    public function getCommand(): CommandInterface;

    public function getHttpResponse(): SymfonyResponseInterface;

    public function success(): bool;

    public function fail(): bool;

    public function getException(): Throwable;
}