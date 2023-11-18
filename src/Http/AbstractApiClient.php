<?php


namespace Feodorpranju\ApiOrm\Http;


use Feodorpranju\ApiOrm\Contracts\Http\ApiClientInterface;
use Feodorpranju\ApiOrm\Contracts\Http\CredentialsInterface;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractApiClient implements ApiClientInterface
{
    protected LoggerInterface $logger;

    public function __construct(protected ?CredentialsInterface $credentials = null)
    {
    }

    #[Pure]
    public static function make(?CredentialsInterface $credentials = null): static
    {
        return new static($credentials);
    }

    /**
     * Gets client's credentials
     *
     * @return CredentialsInterface|null
     */
    public function getCredentials(): ?CredentialsInterface
    {
        return $this->credentials;
    }

    public function setCredentials(?CredentialsInterface $credentials): void
    {
        $this->credentials = $credentials;
    }

    /**
     * Gets client's logger
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger ??= new NullLogger();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}