<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


interface CredentialsInterface
{
    public function getDomainUrl(): string;

    public function setDomainUrl(): void;

    public function getOauthToken(): OauthTokenInterface;

    public function setOauthToken(): void;

    public function getAuthMethod(): string;
}