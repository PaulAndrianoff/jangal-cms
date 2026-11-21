<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Core\Facades\Hook;

class HookManagerTest extends TestCase
{
    public function test_runs_actions_in_priority_order()
    {
        $order = [];

        add_action('test_event', function () use (&$order) { $order[] = 'late'; }, 20);
        add_action('test_event', function () use (&$order) { $order[] = 'early'; }, 5);
        add_action('test_event', function () use (&$order) { $order[] = 'mid'; }, 10);

        do_action('test_event');

        $this->assertEquals(['early', 'mid', 'late'], $order);
    }

    public function test_filters_a_value_and_passes_context_args()
    {
        add_filter('test_value', fn ($v) => $v . '-A');
        add_filter('test_value', fn ($v, $ctx) => $v . '-' . $ctx, 10, 2);

        $result = apply_filters('test_value', 'base', 'ctx');

        $this->assertEquals('base-A-ctx', $result);
    }

    public function test_can_remove_an_action_by_id()
    {
        $called = false;
        $id = add_action('removable', function () use (&$called) { $called = true; });

        $this->assertTrue(has_action('removable', $id));
        $this->assertTrue(remove_action('removable', $id));
        $this->assertFalse(has_action('removable', $id));

        do_action('removable');

        $this->assertFalse($called);
    }

    public function test_supports_the_facade_directly()
    {
        Hook::addFilter('facade_test', fn ($v) => strtoupper($v));

        $this->assertEquals('HELLO', Hook::applyFilters('facade_test', 'hello'));
    }
}