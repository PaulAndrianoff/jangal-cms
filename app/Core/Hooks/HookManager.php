<?php

namespace App\Core\Hooks;

use App\Core\Contracts\HookManagerInterface;

class HookManager implements HookManagerInterface
{
    /**
     * @var array<string, array<int, array<string, array{callback: callable, acceptedArgs: int}>>>
     */
    protected array $actions = [];

    /**
     * @var array<string, array<int, array<string, array{callback: callable, acceptedArgs: int}>>>
     */
    protected array $filters = [];

    protected int $counter = 0;

    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): string
    {
        return $this->add($this->actions, $hook, $callback, $priority, $acceptedArgs);
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): string
    {
        return $this->add($this->filters, $hook, $callback, $priority, $acceptedArgs);
    }

    public function doAction(string $hook, ...$args): void
    {
        if (!isset($this->actions[$hook])) {
            return;
        }

        ksort($this->actions[$hook], SORT_NUMERIC);

        foreach ($this->actions[$hook] as $callbacks) {
            foreach ($callbacks as $entry) {
                $slice = array_slice($args, 0, max(1, $entry['acceptedArgs']));
                call_user_func_array($entry['callback'], $slice);
            }
        }
    }

    public function applyFilters(string $hook, $value, ...$args)
    {
        if (!isset($this->filters[$hook])) {
            return $value;
        }

        ksort($this->filters[$hook], SORT_NUMERIC);

        foreach ($this->filters[$hook] as $callbacks) {
            foreach ($callbacks as $entry) {
                $allArgs = array_merge([$value], $args);
                $slice = array_slice($allArgs, 0, max(1, $entry['acceptedArgs']));
                $value = call_user_func_array($entry['callback'], $slice);
            }
        }

        return $value;
    }

    public function removeAction(string $hook, string $id): bool
    {
        return $this->remove($this->actions, $hook, $id);
    }

    public function removeFilter(string $hook, string $id): bool
    {
        return $this->remove($this->filters, $hook, $id);
    }

    public function hasAction(string $hook, ?string $id = null): bool
    {
        return $this->has($this->actions, $hook, $id);
    }

    public function hasFilter(string $hook, ?string $id = null): bool
    {
        return $this->has($this->filters, $hook, $id);
    }

    public function removeAllActions(?string $hook = null): void
    {
        $this->removeAll($this->actions, $hook);
    }

    public function removeAllFilters(?string $hook = null): void
    {
        $this->removeAll($this->filters, $hook);
    }

    /* ------------------------------------------------------------------ */
    /* Internals                                                           */
    /* ------------------------------------------------------------------ */

    protected function add(array &$bag, string $hook, callable $callback, int $priority, int $acceptedArgs): string
    {
        $id = $this->generateId($callback);
        $bag[$hook][$priority][$id] = [
            'callback' => $callback,
            'acceptedArgs' => $acceptedArgs,
        ];

        return $id;
    }

    protected function remove(array &$bag, string $hook, string $id): bool
    {
        if (!isset($bag[$hook])) {
            return false;
        }

        foreach ($bag[$hook] as $priority => $callbacks) {
            if (isset($callbacks[$id])) {
                unset($bag[$hook][$priority][$id]);

                if (empty($bag[$hook][$priority])) {
                    unset($bag[$hook][$priority]);
                }

                if (empty($bag[$hook])) {
                    unset($bag[$hook]);
                }

                return true;
            }
        }

        return false;
    }

    protected function has(array $bag, string $hook, ?string $id): bool
    {
        if (!isset($bag[$hook])) {
            return false;
        }

        if ($id === null) {
            return true;
        }

        foreach ($bag[$hook] as $callbacks) {
            if (isset($callbacks[$id])) {
                return true;
            }
        }

        return false;
    }

    protected function removeAll(array &$bag, ?string $hook): void
    {
        if ($hook === null) {
            $bag = [];
            return;
        }

        unset($bag[$hook]);
    }

    protected function generateId(callable $callback): string
    {
        $this->counter++;

        // Identifiant lisible + unique par callback
        $prefix = is_string($callback)
            ? 'fn'
            : (is_array($callback) ? 'm' : 'c');

        return $prefix . '_' . $this->counter . '_' . substr(md5(uniqid('', true)), 0, 8);
    }
}