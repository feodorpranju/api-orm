<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Feodorpranju\ApiOrm\Contracts\Http\ApiClientInterface;

/**
 * Interface HasClientInterface
 * @package Feodorpranju\ApiOrm\Contracts
 *
 * @method static static withClient(ApiClientInterface $client)
 */
interface HasClientInterface
{
    /**
     * Sets api client to model
     *
     * @param ApiClientInterface|null $client
     * @return $this
     */
    public function setClient(?ApiClientInterface $client): static;

    /**
     * Gets model's client
     *
     * @return ApiClientInterface|null
     */
    public function getClient(): ?ApiClientInterface;
}