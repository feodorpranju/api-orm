<?php


namespace Feodorpranju\ApiOrm\Contracts;


use ArrayAccess;
use Feodorpranju\ApiOrm\Contracts\Http\ApiClientInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Feodorpranju\ApiOrm\Enumerations\FieldGetMode;

interface ModelInterface extends HasDumpInterface, Arrayable, ArrayAccess, HasClientInterface
{
    /**
     * Creates Model and sets it's fields
     *
     * @param array|Collection $attributes
     */
    public function __construct(array|Collection $attributes = []);

    /**
     * Gets record from bitrix24 as model
     *
     * @param int|string $id Entity id
     * @return ModelInterface|null
     */
    public function get(int|string $id): ?static;

    /**
     * Gets collection of field settings
     *
     * @return Collection
     * @see FieldSettings
     */
    public function fields(): Collection;

    /**
     * Sets values by keys as field names
     *
     * @param array|Collection $values
     * @return $this
     */
    public function put(array|Collection $values): static;

    /**
     * @param string $name
     * @param FieldGetMode|null $mode
     * @return mixed
     */
    public function getAs(string $name, FieldGetMode $mode = null): mixed;

    /**
     * Gets collection of given fields. All if null;
     *
     * @param array|null $names
     * @param FieldGetMode|null $mode
     * @return Collection
     */
    public function only(?array $names = null, ?FieldGetMode $mode = null): Collection;

    /**
     * Gets collection of fields except given ones. All if empty;
     *
     * @param array $names
     * @param FieldGetMode|null $mode
     * @return Collection
     */
    public function except(array $names = [], ?FieldGetMode $mode = null): Collection;

    /**
     * Преобразует данные полей в массив
     *
     * @param FieldGetMode|null $mode
     * @return array
     */
    public function toArray(?FieldGetMode $mode = null): array;

    /**
     * Creates object of model with given data.<br/>
     * Equals to <b>new static($attributes)</b>;
     *
     * @param array|Collection $attributes Field data
     * @return static
     */
    public static function make(array|Collection $attributes = []): static;

    /**
     * Collects array|collection of fields arrays|collections to model collection
     *
     * @param array|Collection $collection
     * @param ApiClientInterface|null $client
     * @return Collection
     * @see Make
     */
    public function collect(array|Collection $collection, ?ApiClientInterface $client = null): Collection;
}