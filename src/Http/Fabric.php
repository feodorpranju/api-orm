<?php


namespace Feodorpranju\ApiOrm\Http;


use Feodorpranju\ApiOrm\Exceptions\Http\BadGatewayException;
use Feodorpranju\ApiOrm\Exceptions\Http\BadRequestException;
use Feodorpranju\ApiOrm\Exceptions\Http\BaseHttpException;
use Feodorpranju\ApiOrm\Exceptions\Http\ForbiddenException;
use Feodorpranju\ApiOrm\Exceptions\Http\GatewayTimeoutException;
use Feodorpranju\ApiOrm\Exceptions\Http\InternalServerErrorException;
use Feodorpranju\ApiOrm\Exceptions\Http\NotFoundException;
use Feodorpranju\ApiOrm\Exceptions\Http\PaymentRequiredException;
use Feodorpranju\ApiOrm\Exceptions\Http\RequestTimeoutException;
use Feodorpranju\ApiOrm\Exceptions\Http\ServiceUnavailableException;
use Feodorpranju\ApiOrm\Exceptions\Http\TooManyRequestsException;
use Feodorpranju\ApiOrm\Exceptions\Http\UnauthorizedException;

class Fabric
{
    protected static array $exceptionMap = [
        400 => BadRequestException::class,
        401 => UnauthorizedException::class,
        402 => PaymentRequiredException::class,
        403 => ForbiddenException::class,
        404 => NotFoundException::class,
        405 => RequestTimeoutException::class,
        408 => RequestTimeoutException::class,
        429 => TooManyRequestsException::class,
        500 => InternalServerErrorException::class,
        502 => BadGatewayException::class,
        503 => ServiceUnavailableException::class,
        504 => GatewayTimeoutException::class
    ];

    /**
     * Returns http exception instance by http code
     *
     * @param int $code
     * @param string $message
     * @return mixed
     */
    public static function getExceptionByHttpCode(int $code, string $message = ''): BaseHttpException
    {
        $className = BaseHttpException::class;

        if (isset(static::$exceptionMap[$code])) {
            $className = static::$exceptionMap[$code];
        } elseif ($code >= 400 && $code < 500) {
            $className = BadRequestException::class;
        } elseif ($code >= 500) {
            $className = InternalServerErrorException::class;
        }

        return $className::make($message, $code);
    }
}