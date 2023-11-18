<?php


namespace Feodorpranju\ApiOrm\Contracts\Http;


use Feodorpranju\ApiOrm\Exceptions\Http\BaseHttpException;
use Illuminate\Http\Client\Response;
use Throwable;


interface ResponseInterface
{
    public function __construct(Response $httpResponse, ?CommandInterface $command = null);

    /**
     * @param Response $response
     * @param CommandInterface|null $command
     * @return static
     */
    public static function make(Response $response, ?CommandInterface $command): static;

    /**
     * Gets formatted response
     *
     * @return array|object
     */
    public function getResult(): array|object;

    /**
     * Gets executed command
     *
     * @return CommandInterface|null
     */
    public function getCommand(): ?CommandInterface;

    /**
     * Gets HTTP response
     *
     * @return Response
     */
    public function getHttpResponse(): Response;

    /**
     * Check is http response has good http code
     *
     * @return bool
     */
    public function successful(): bool;

    /**
     * Check is http response has bad http code
     *
     * @return bool
     */
    public function failed(): bool;

    /**
     * Gets HTTP exception by code
     *
     * @return Throwable|null
     */
    public function toException(): ?Throwable;

    /**
     * @throws BaseHttpException
     */
    public function throw(): void;
}