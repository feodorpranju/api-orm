<?php


namespace Feodorpranju\ApiOrm\Models;


use Feodorpranju\ApiOrm\Contracts\ModelInterface;
use Feodorpranju\ApiOrm\Contracts\QueryBuilderInterface;
use Feodorpranju\ApiOrm\Exceptions\InstanceException;
use Feodorpranju\ApiOrm\Exceptions\InvalidOperandException;
use Feodorpranju\ApiOrm\Exceptions\InvalidOrderDirectionException;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

abstract class AbstractQueryBuilder implements QueryBuilderInterface
{
    protected Collection $conditions;
    protected const AVAILABLE_OPERANDS = ["=", ">", "<", ">=", "<=", "!", "<>"];
    protected const AVAILABLE_DIRECTIONS = ["ASC", "DESC"];
    protected const GENERATOR_LOOP_LIMIT = 1000000;

    protected string $orderField;
    protected string $orderDirection;

    /**
     * The number select starts at
     *
     * @var int
     */
    protected int $offset = 0;

    /**
     * The limit of chunk select
     *
     * @var int
     */
    protected int $chunkSize = 50;

    /**
     * The limit of total select
     *
     * @var int
     */
    protected int $limit = 0;

    /**
     * The number of current chunk
     *
     * @var int
     */
    protected int $current = 1;

    /**
     * List of fields to be selected
     *
     * @var array
     */
    protected array $select = [];

    public function __construct(protected string $model)
    {
        if (!is_a($model, ModelInterface::class, true)) {
            throw new InstanceException("Model '$model' is not instance of ".ModelInterface::class);
        }
        $this->conditions = collect([]);
    }

    /**
     * @inheritdoc
     */
    public function select(array $fields = []): static {
        $this->select = $fields;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function where(string $field, mixed $operand = null, mixed $value = null): static
    {
        $tValue = $value ?? $operand ?? true;
        $tOperand = $value === null ? "=" : ($operand ?? "=");

        if (!in_array($tOperand, static::AVAILABLE_OPERANDS)) {
            throw new InvalidOperandException("Invalid operand '$tOperand' in ".static::class);
        }

        $this->conditions->push([$field, $tOperand, $tValue]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function whereFirst(string $field, mixed $operand = null, mixed $value = null): ?ModelInterface
    {
        return $this->where($field, $operand, $value)->first();
    }

    /**
     * @inheritdoc
     */
    public function first(): ?ModelInterface
    {
        return $this->lazy()->first();
    }

    /**
     * @inheritdoc
     */
    public function firstOrFail(): ModelInterface
    {
        return $this->lazy()->firstOrFail();
    }

    /**
     * @inheritdoc
     */
    public function all(): Collection
    {
        return $this->lazy()->collect();
    }

    /**
     * @inheritdoc
     */
    public function lazy(): LazyCollection
    {
        return new LazyCollection(function() {return $this->generator();});
    }

    /**
     * Creates generator for lazy collection
     *
     * @return Generator
     */
    protected function generator(): Generator
    {
        $page = 0;
        $i = 0;
        do {
            $page++;
            $models = $this->forPage($page, $this->chunkSize)->get();

            foreach ($models as $model) {
                if ($i++ > static::GENERATOR_LOOP_LIMIT) {
                    break;
                }
                yield $model;
            }
        } while (
            $models->count() !== 0
            && ($page-1)*$this->chunkSize+$models->count() < $this->count()
        );
    }

    /**
     * @inheritdoc
     */
    public function chunk(int|null $chunkSize, callable $callback): bool
    {
        $page = 1;

        do {
            $models = $this->forPage($page, $chunkSize);

            if ($callback($models, $page, $chunkSize) === false) {
                return false;
            }

            $page++;
        } while (
            $models->count() !== 0
            && ($page-1)*$chunkSize+$models->count() !== $this->count()
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function offset(int $offset): static
    {
        $this->offset = $offset < 0 ? 0 : $offset;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function order(string $field, string $direction = "ASC"): static
    {
        $this->orderField = $field;

        if (!in_array($direction, static::AVAILABLE_DIRECTIONS)) {
            throw new InvalidOrderDirectionException("Invalid order direction '$direction' in ".static::class);
        }

        $this->orderDirection = $direction;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit < 0 ? 0 :$limit;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return ($this->model)::count($this->conditions);
    }

    /**
     * @inheritdoc
     */
    public function forPage(int $page, ?int $perPage = null): static
    {
        $perPage ??= $this->chunkSize;
        $this->chunkSize = (int)($perPage < 1 ? 1 : $perPage);
        $this->offset(($page-1)*$this->chunkSize);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get(): Collection
    {
        return ($this->model)::find(
            $this->conditions,
            $this->orderField ?? null,
            $this->orderDirection ?? null,
            $this->select,
            $this->offset,
            $this->chunkSize
        );
    }
}