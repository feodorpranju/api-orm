<?php


namespace Feodorpranju\ApiOrm\Http;


use Feodorpranju\ApiOrm\Contracts\Http\ApiClientInterface;
use Feodorpranju\ApiOrm\Contracts\Http\BatchInterface;
use Feodorpranju\ApiOrm\Contracts\Http\BatchResponseInterface;
use Feodorpranju\ApiOrm\Contracts\Http\CommandInterface;
use Feodorpranju\ApiOrm\Exceptions\InvalidArgumentException;
use Illuminate\Support\Collection;

abstract class AbstractBatch extends Collection implements BatchInterface
{
    /**
     * @inheritDoc
     */
    public function call(): BatchResponseInterface
    {

    }

    /**
     * Batch constructor.
     * @param mixed|array $items
     * @param ApiClientInterface|null $apiClient
     */
    public function __construct(mixed $items = [], ApiClientInterface $apiClient = null)
    {
        foreach ($items as $item) {
            static::validateItem($item);
        }
        parent::__construct($items);
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    public function push(...$values): static
    {
        foreach ($values as $value) {
            static::validateItem($value);
        }
        return parent::push(...$values);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return AbstractBatch
     */
    public function put($key, $value): AbstractBatch
    {
        static::validateItem($value);
        return parent::put($key, $value);
    }

    /**
     * Validates item
     *
     * @param mixed $item
     * @throws InvalidArgumentException
     */
    protected function validateItem(mixed $item): void
    {
        if (!is_object($item) || !($item instanceof CommandInterface)) {
            throw new InvalidArgumentException("Item should be instance of ".CommandInterface::class);
        }
    }
}