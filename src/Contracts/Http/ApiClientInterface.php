<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


use Psr\Log\LoggerInterface;

interface ApiClientInterface
{
    public function call(string $action, array $params = [], string $method = 'POST'): ResponseInterface;

    public function getCredentials(): ?CredentialsInterface;

    public function setCredentials(?CredentialsInterface $credentials): void;

    public function getLogger(): LoggerInterface;

    public function setLogger(LoggerInterface $logger): void;
}