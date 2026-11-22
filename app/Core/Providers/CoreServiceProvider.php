<?php

namespace App\Core\Providers;

use App\Core\Contracts\HookManagerInterface;
use App\Core\Hooks\HookManager;
use App\Core\Widgets\WidgetManager;
use App\Core\Themes\ThemeManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 1. Enregistrement des singletons du noyau
        $this->app->singleton('hook', fn() => new HookManager());
        $this->app->bind(HookManagerInterface::class, fn($app) => $app->make('hook'));
        $this->app->singleton('widget', fn() => new WidgetManager());

        // 2. Chargement dynamique des plugins
        $this->loadPlugins();
    }

    public function boot(): void
    {
        // Chargement du thème actif
        $this->app->make(ThemeManager::class)->loadActiveTheme();
    }

    protected function loadPlugins()
    {
        try {
            $activePlugins = DB::table('plugins')->where('is_active', true)->pluck('name')->toArray();
        } catch (\Exception $e) {
            return; // La base de données n'est pas prête
        }

        foreach ($activePlugins as $slug) {
            $providerClass = "Platform\\Plugins\\" . str_replace('-', '', ucwords($slug, '-')) . "\\Providers\\PluginServiceProvider";

            // 1. Si Composer ne trouve pas la classe, on inclut le fichier manuellement
            if (!class_exists($providerClass)) {
                $providerFile = base_path("platform/plugins/{$slug}/Providers/PluginServiceProvider.php");
                if (file_exists($providerFile)) {
                    require_once $providerFile;
                }
            }

            // 2. Une fois le fichier inclus, on enregistre le provider
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }
}
