<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


interface CredentialsInterface
{
    public function getDomainUrl(): string;

    public function setDomainUrl(string $domain): void;

    public function getAuthMethod(): string;
}