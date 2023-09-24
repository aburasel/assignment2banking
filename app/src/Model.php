<?php
declare(strict_types=1);
namespace App\src;

interface Model
{
    public static function getModelName(): string;
}