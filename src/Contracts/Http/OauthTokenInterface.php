<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


use Carbon\Carbon;

interface OauthTokenInterface
{
    public function getAuthToken(): string;

    public function getRefreshToken(): string;

    public function getExpiresAt(): Carbon;

    public function isExpired(): bool;
}