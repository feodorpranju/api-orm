<?php


namespace Feodorpranju\ApiOrm\Http;


use Feodorpranju\ApiOrm\Contracts\Http\CommandInterface;
use Feodorpranju\ApiOrm\Contracts\Http\ResponseInterface;
use Illuminate\Http\Client\Response;
use JetBrains\PhpStorm\Pure;
use Throwable;


abstract class AbstractResponse implements ResponseInterface
{
    public function __construct(protected Response $httpResponse, protected ?CommandInterface $command = null)
    {

    }

    /**
     * @inheritdoc
     */
    #[Pure]
    public static function make(Response $response, ?CommandInterface $command = null): static
    {
        return new static($response, $command);
    }

    /**
     * @inheritdoc
     */
    public function getCommand(): ?CommandInterface
    {
        return $this->command;
    }

    /**
     * @inheritdoc
     */
    public function getHttpResponse(): Response
    {
        return $this->httpResponse;
    }

    /**
     * @inheritdoc
     */
    public function successful(): bool
    {
        return $this->getHttpResponse()->successful();
    }

    /**
     * @inheritdoc
     */
    public function failed(): bool
    {
        return $this->getHttpResponse()->failed();
    }

    /**
     * @inheritdoc
     */
    public function toException(): ?Throwable
    {
        return $this->getHttpResponse()->toException();
    }

    public function throw(): void
    {
        if ($this->failed()) {
            $this->throw();
        }
    }
}