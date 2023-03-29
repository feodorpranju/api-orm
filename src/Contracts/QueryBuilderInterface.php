<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

interface QueryBuilderInterface
{
    public function where(string $fieldID, mixed $operand, mixed $subject): self;

    public function first(): ?ModelInterface;

    public function firstOrFail(): ModelInterface;

    public function all(): Collection;

    public function lazy(): LazyCollection;

    public function chunk(int $start, int $limit): self;

    public function offset(int $offset): self;

    public function order(int $fieldID, string $direction): self;

    public function limit(int $limit): self;

    public function count(): int;
}