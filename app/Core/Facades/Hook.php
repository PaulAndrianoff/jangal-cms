<?php

namespace App\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string addAction(string $hook, callable $cb, int $priority = 10, int $acceptedArgs = 1)
 * @method static string addFilter(string $hook, callable $cb, int $priority = 10, int $acceptedArgs = 1)
 * @method static void doAction(string $hook, mixed ...$args)
 * @method static mixed applyFilters(string $hook, mixed $value, mixed ...$args)
 * @method static bool removeAction(string $hook, string $id)
 * @method static bool removeFilter(string $hook, string $id)
 * @method static bool hasAction(string $hook, ?string $id = null)
 * @method static bool hasFilter(string $hook, ?string $id = null)
 * @method static void removeAllActions(?string $hook = null)
 * @method static void removeAllFilters(?string $hook = null)
 *
 * @see \App\Core\Hooks\HookManager
 */
class Hook extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'hook';
    }
}