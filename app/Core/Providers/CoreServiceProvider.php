<?php

namespace App\Core\Providers;

use App\Core\Contracts\HookManagerInterface;
use App\Core\Hooks\HookManager;
use Illuminate\Support\ServiceProvider;
use App\Core\Plugins\PluginManager;
use App\Core\Themes\ThemeManager;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('hook', fn () => new HookManager());

        $this->app->bind(HookManagerInterface::class, fn ($app) => $app->make('hook'));
    }

    public function boot(): void
    {
        // Chargement dynamique des plugins actifs
        $this->app->make(PluginManager::class)->loadActivePlugins();
        $this->app->make(ThemeManager::class)->loadActiveTheme();
    }
}