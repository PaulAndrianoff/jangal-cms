<?php

namespace App\Core\Plugins;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class PluginManager
{
    protected $pluginsPath;

    public function __construct()
    {
        $this->pluginsPath = base_path('platform/plugins');
    }

    /**
     * Récupère tous les plugins présents sur le disque dur
     */
    public function getAvailablePlugins()
    {
        if (!File::exists($this->pluginsPath)) {
            return [];
        }

        $directories = File::directories($this->pluginsPath);
        $plugins = [];

        foreach ($directories as $dir) {
            $manifestPath = $dir . '/plugin.json';
            if (File::exists($manifestPath)) {
                $manifest = json_decode(File::get($manifestPath), true);
                $manifest['slug'] = basename($dir);
                $manifest['path'] = $dir;
                $plugins[] = $manifest;
            }
        }

        return $plugins;
    }

    /**
     * Active un plugin dans la base de données
     */
    public function activate($slug)
    {
        DB::table('plugins')->updateOrInsert(
            ['name' => $slug],
            ['is_active' => true, 'updated_at' => now()]
        );
    }

    /**
     * Désactive un plugin
     */
    public function deactivate($slug)
    {
        DB::table('plugins')->where('name', $slug)->update(['is_active' => false]);
    }
}
