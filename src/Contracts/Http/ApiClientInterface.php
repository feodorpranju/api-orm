<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


interface ApiClientInterface
{
    public function call(): ResponseInterface;

    public function getCredentials(): CredentialsInterface;

    public function setCredentials(): void;
}