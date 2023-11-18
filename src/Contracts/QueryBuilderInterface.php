<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Feodorpranju\ApiOrm\Contracts\Http\ApiClientInterface;
use Feodorpranju\ApiOrm\Exceptions\InvalidOrderDirectionException;
use Feodorpranju\ApiOrm\Support\ModelCollection;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\LazyCollection;

/**
 * Interface QueryBuilderInterface
 * @package Feodorpranju\ApiOrm\Contracts
 *
 * @method static static withClient(ApiClientInterface $client)
 */
interface QueryBuilderInterface extends HasDumpInterface, HasClientInterface
{
    /**
     * Sets fields to be selected
     *
     * @param array $fields
     * @return $this
     */
    public function select(array $fields = []): static;

    /**
     * Sets condition. Uses AND logic.
     *
     * @param string|array $field
     * @param mixed|null $operand
     * @param mixed|null $value
     * @return $this
     */
    public function where(string|array $field, mixed $operand = null, mixed $value = null): static;

    /**
     * Sets where condition and gets first.
     *
     * @param string $field
     * @param mixed|null $operand
     * @param mixed|null $value
     * @return ModelInterface|null
     * @see QueryBuilderInterface::where()
     * @see QueryBuilderInterface::first()
     */
    public function whereFirst(string $field, mixed $operand = null, mixed $value = null): ?ModelInterface;

    /**
     * Gets first item. Null if not found.
     *
     * @return ModelInterface|null
     */
    public function first(): ?ModelInterface;

    /**
     * Gets first item or throws.
     *
     * @return ModelInterface
     * @throws ItemNotFoundException
     */
    public function firstOrFail(): ModelInterface;

    /**
     * Gets all items for select.
     *
     * @return ModelCollection
     */
    public function all(): ModelCollection;

    /**
     * Gets lazy collection for select.
     *
     * @return LazyCollection
     */
    public function lazy(): LazyCollection;

    /**
     * Runs callback for every chunk received by filters
     *
     * @param int|null $chunkSize
     * @param callable $callback
     * @return bool
     * @see QueryBuilderInterface::offset()
     */
    public function chunk(int|null $chunkSize, callable $callback): bool;

    /**
     * Sets initial offset of which selects starts with.
     * Zero is start point.
     * Sets to zero if under it.
     *
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static;

    /**
     * Sets order by field and direction.
     *
     * @param string $field
     * @param string $direction
     * @return $this
     * @throws InvalidOrderDirectionException
     */
    public function order(string $field, string $direction = "ASC"): static;

    /**
     * Sets chunk limit of select.
     * No limit if equals to zero or under it.
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static;

    /**
     * Gets count of items with current filter.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Sets offset and chunkSize for page
     *
     * @param int $page
     * @param int|null $perPage
     * @return $this
     */
    public function forPage(int $page, ?int $perPage = null): static;

    /**
     * Gets collection for current offset and chunkSize
     *
     * @return ModelCollection
     */
    public function get(): ModelCollection;
}