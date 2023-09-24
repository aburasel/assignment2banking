<?php declare(strict_types=1);
namespace App\src;

interface Storage
{
    public function save(string $model, array $data): void;

    public function load(string $model): array;

    public function getModelPath(string $model);
}