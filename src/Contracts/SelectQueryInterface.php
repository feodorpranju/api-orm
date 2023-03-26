<?php


namespace Feodorpranju\ApiOrm\Contracts;


use Illuminate\Support\Collection;

interface SelectQueryInterface
{
    public function andWhere(string $fieldID, mixed $operand, mixed $subject): SelectQueryInterface;

    public function first(): ?ModelInterface;

    public function firstOrFail(): ModelInterface;

    public function all(): Collection;

    public function chunk(int $start, int $limit): SelectQueryInterface;

    public function offset(int $offset): SelectQueryInterface;

    public function order(int $fieldID, string $direction): SelectQueryInterface;
}