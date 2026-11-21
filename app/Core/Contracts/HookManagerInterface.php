<?php

namespace App\Core\Contracts;

interface HookManagerInterface
{
    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): string;

    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): string;

    public function doAction(string $hook, ...$args): void;

    public function applyFilters(string $hook, $value, ...$args);

    public function removeAction(string $hook, string $id): bool;

    public function removeFilter(string $hook, string $id): bool;

    public function hasAction(string $hook, ?string $id = null): bool;

    public function hasFilter(string $hook, ?string $id = null): bool;

    public function removeAllActions(?string $hook = null): void;

    public function removeAllFilters(?string $hook = null): void;
}