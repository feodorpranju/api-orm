<?php


namespace Feodorpranju\ApiOrm\Contracts;


interface HasDumpInterface
{
    /**
     * Shows object's debug info and dies
     *
     * @param mixed ...$args
     * @return mixed
     */
    public function dd(...$args): void;

    /**
     * Shows object's debug info
     *
     * @param mixed ...$args
     * @return mixed
     */
    public function dump(...$args): static;
}