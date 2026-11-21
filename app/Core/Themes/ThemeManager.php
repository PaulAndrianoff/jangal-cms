<?php

namespace App\Core\Themes;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ThemeManager
{
    protected $themesPath;

    public function __construct()
    {
        $this->themesPath = base_path('platform/themes');
    }

    /**
     * Récupère tous les thèmes présents sur le disque
     */
    public function getAvailableThemes()
    {
        if (!File::exists($this->themesPath)) {
            return [];
        }

        $directories = File::directories($this->themesPath);
        $themes = [];

        foreach ($directories as $dir) {
            $manifestPath = $dir . '/theme.json';
            if (File::exists($manifestPath)) {
                $manifest = json_decode(File::get($manifestPath), true);
                $manifest['slug'] = basename($dir);
                $manifest['path'] = $dir;
                $themes[] = $manifest;
            }
        }

        return $themes;
    }

    /**
     * Récupère le thème actuellement actif en BDD
     */
    public function getActiveTheme()
    {
        try {
            return DB::table('themes')->where('is_active', true)->first();
        } catch (\Exception $e) {
            return null; // BDD pas prête
        }
    }

    /**
     * Charge le thème actif et prépare la surcharge des vues
     */
    public function loadActiveTheme()
    {
        $activeTheme = $this->getActiveTheme();

        if (!$activeTheme) {
            return;
        }

        $themePath = $this->themesPath . '/' . $activeTheme->name;

        if (File::exists($themePath . '/views')) {
            // On donne un nom unique à l'espace de nom des vues du thème
            View::addNamespace('theme', $themePath . '/views');
        }

        // 2. Surcharge des vues des plugins (ex: vendor/hello-world/index.blade.php)
        // Si un plugin utilise View::make('hello-world::index'), le thème peut le surcharger
        // en créant le fichier : platform/themes/mon-theme/views/vendor/hello-world/index.blade.php
        $vendorViewsPath = $themePath . '/views/vendor';

        if (File::exists($vendorViewsPath)) { // <-- AJOUTE CETTE VÉRIFICATION
            foreach (File::directories($vendorViewsPath) as $vendorDir) {
                $namespace = basename($vendorDir);
                View::prependNamespace($namespace, $vendorDir);
            }
        }
    }

    /**
     * Active un thème (et désactive les autres)
     */
    public function activate($slug)
    {
        // Désactive tous les thèmes
        DB::table('themes')->update(['is_active' => false]);

        // Active le thème choisi
        DB::table('themes')->updateOrInsert(
            ['name' => $slug],
            ['is_active' => true, 'updated_at' => now()]
        );
    }

    public function getThemeOptions($slug)
    {
        $path = $this->themesPath . '/' . $slug . '/theme.json';
        if (File::exists($path)) {
            $manifest = json_decode(File::get($path), true);
            return $manifest['options'] ?? [];
        }
        return [];
    }

    public function updateThemeOptions($slug, $options)
    {
        $path = $this->themesPath . '/' . $slug . '/theme.json';
        if (File::exists($path)) {
            $manifest = json_decode(File::get($path), true);
            $manifest['options'] = array_merge($manifest['options'] ?? [], $options);
            File::put($path, json_encode($manifest, JSON_PRETTY_PRINT));
        }
    }
}
