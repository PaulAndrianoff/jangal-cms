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

    // 1. Uploader un thème (fichier ZIP)
    public function uploadTheme($file)
    {
        $zip = new \ZipArchive();
        if ($zip->open($file->getRealPath()) === TRUE) {
            $zip->extractTo($this->themesPath);
            $zip->close();
            return true;
        }
        return false;
    }

    // 2. Supprimer un thème
    public function deleteTheme($slug)
    {
        $activeTheme = $this->getActiveTheme();
        $themes = $this->getAvailableThemes();

        // Sécurité 1 : Impossible de supprimer le thème actif
        if ($activeTheme && $activeTheme->name === $slug) {
            return "Impossible de supprimer le thème actif. Activez-en un autre d'abord.";
        }

        // Sécurité 2 : Impossible de supprimer s'il ne reste qu'un seul thème
        if (count($themes) <= 1) {
            return "Impossible de supprimer le dernier thème disponible.";
        }

        $themePath = $this->themesPath . '/' . $slug;
        if (File::exists($themePath)) {
            File::deleteDirectory($themePath);
            return true;
        }
        return "Thème introuvable sur le disque.";
    }

    // 3. Modifier les infos du thème (theme.json)
    public function updateThemeManifest($slug, $data)
    {
        $path = $this->themesPath . '/' . $slug . '/theme.json';
        if (File::exists($path)) {
            $manifest = json_decode(File::get($path), true);

            // On met à jour uniquement les champs modifiables
            $manifest['name'] = $data['name'];
            $manifest['description'] = $data['description'] ?? '';
            $manifest['version'] = $data['version'];
            $manifest['author'] = $data['author'] ?? '';

            File::put($path, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return true;
        }
        return false;
    }
}
