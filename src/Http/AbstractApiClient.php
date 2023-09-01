<?php


namespace Feodorpranju\ApiOrm\Http;


use Feodorpranju\ApiOrm\Contracts\Http\ApiClientInterface;
use Feodorpranju\ApiOrm\Contracts\Http\CredentialsInterface;
use Feodorpranju\ApiOrm\Contracts\Http\ResponseInterface;
use Psr\Log\LoggerInterface;

class ApiClient implements ApiClientInterface
{
    protected LoggerInterface $logger;
    protected CredentialsInterface $credentials;

    public function call(): ResponseInterface
    {
        // TODO: Implement call() method.
    }

    public function getCredentials(): CredentialsInterface
    {
        // TODO: Implement getCredentials() method.
    }

    public function setCredentials(): void
    {
        // TODO: Implement setCredentials() method.
    }

    public function setLogger(): void
    {
        // TODO: Implement setLogger() method.
    }

    public function getLogger(): LoggerInterface
    {

    }
}