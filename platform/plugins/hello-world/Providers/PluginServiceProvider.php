<?php

namespace Platform\Plugins\HelloWorld\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Ce plugin ajoute automatiquement une majuscule via notre HookManager !
        add_filter('test_plugin_hook', function($value) {
            return $value . ' (Modifié par Hello World)';
        });
    }
}