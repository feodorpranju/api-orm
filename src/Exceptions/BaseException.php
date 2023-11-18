<?php


namespace Feodorpranju\ApiOrm\Exceptions;


use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class BaseException extends Exception
{
    /**
     * Creates an instance of this class
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @return static
     */
    #[Pure]
    public static function make(string $message = '', int $code = 0, Throwable $previous = null): static
    {
        return new static($message, $code, $previous);
    }
}