<?php

namespace App\Core\Plugins;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use ZipArchive;
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

    // Uploader un plugin ZIP
    public function uploadPlugin($file)
    {
        $zip = new ZipArchive();
        if ($zip->open($file->getRealPath()) === TRUE) {
            $zip->extractTo($this->pluginsPath);
            $zip->close();
            return true;
        }
        return false;
    }

    // Éditer les infos du plugin (plugin.json)
    public function updatePluginManifest($slug, $data)
    {
        $path = $this->pluginsPath . '/' . $slug . '/plugin.json';
        if (File::exists($path)) {
            $manifest = json_decode(File::get($path), true);

            $manifest['name'] = $data['name'];
            $manifest['description'] = $data['description'] ?? '';
            $manifest['version'] = $data['version'];
            $manifest['author'] = $data['author'] ?? '';

            File::put($path, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return true;
        }
        return false;
    }

    // Supprimer un plugin
    public function deletePlugin($slug)
    {
        $pluginPath = $this->pluginsPath . '/' . $slug;

        if (File::exists($pluginPath)) {
            // On supprime du disque
            File::deleteDirectory($pluginPath);

            // On supprime de la base de données
            DB::table('plugins')->where('name', $slug)->delete();

            return true;
        }
        return false;
    }
}
