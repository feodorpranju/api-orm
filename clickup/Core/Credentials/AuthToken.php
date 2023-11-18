<?php


namespace Feodorpranju\ClickUp\SDK\Core;


class AuthToken implements \Feodorpranju\ApiOrm\Contracts\Http\CredentialsInterface
{
    protected string $url = 'https://api.clickup.com/api/';

    public function __construct(protected string $authToken)
    {
    }

    public function getDomainUrl(): string
    {
        return $this->url;
    }

    public function setDomainUrl(string $domain): void
    {
        $this->url = $domain;
    }

    public function getAuthMethod(): string
    {
        return 'bearer';
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }
}