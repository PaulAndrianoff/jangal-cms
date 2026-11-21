<?php

use App\Core\Facades\Hook;

if (!function_exists('add_action')) {
    function add_action(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): string
    {
        return Hook::addAction($hook, $callback, $priority, $acceptedArgs);
    }
}

if (!function_exists('add_filter')) {
    function add_filter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): string
    {
        return Hook::addFilter($hook, $callback, $priority, $acceptedArgs);
    }
}

if (!function_exists('do_action')) {
    function do_action(string $hook, ...$args): void
    {
        Hook::doAction($hook, ...$args);
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters(string $hook, $value, ...$args)
    {
        return Hook::applyFilters($hook, $value, ...$args);
    }
}

if (!function_exists('remove_action')) {
    function remove_action(string $hook, string $id): bool
    {
        return Hook::removeAction($hook, $id);
    }
}

if (!function_exists('remove_filter')) {
    function remove_filter(string $hook, string $id): bool
    {
        return Hook::removeFilter($hook, $id);
    }
}

if (!function_exists('has_action')) {
    function has_action(string $hook, ?string $id = null): bool
    {
        return Hook::hasAction($hook, $id);
    }
}

if (!function_exists('has_filter')) {
    function has_filter(string $hook, ?string $id = null): bool
    {
        return Hook::hasFilter($hook, $id);
    }
}