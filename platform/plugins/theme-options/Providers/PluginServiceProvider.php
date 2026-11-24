<?php

namespace Platform\Plugins\ThemeOptions\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class PluginServiceProvider extends ServiceProvider
{
    protected function getJson($filename)
    {
        $path = base_path('platform/plugins/theme-options/' . $filename);
        return File::exists($path) ? json_decode(File::get($path), true) : [];
    }

    public function boot()
    {
        $schema = $this->getJson('schema.json');
        $config = $this->getJson('config.json');

        // 1. Génération dynamique du CSS
        add_action('theme_head', function() use ($schema, $config) {
            $css = "<style>\n:root {\n";
            
            foreach ($schema as $field) {
                // On ne génère des variables CSS que pour les couleurs et le texte
                if (in_array($field['type'], ['color', 'text'])) {
                    $value = $config[$field['name']] ?? $field['default'];
                    $cssVarName = str_replace('_', '-', $field['name']);
                    $css .= "  --{$cssVarName}: {$value};\n";
                }
            }
            $css .= "}\n";

            // Application des polices
            $font = $config['font_family'] ?? "'Segoe UI', sans-serif";
            $css .= "body { font-family: {$font} !important; }\n";
            
            // Application des couleurs aux éléments du thème
            $css .= "
                .navbar-custom { background-color: var(--primary-color) !important; }
                .bg-primary { background-color: var(--primary-color) !important; }
                .btn-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; }
                .hero { background-color: var(--secondary-color) !important; }
            ";

            // CSS personnalisé
            if (!empty($config['custom_css'])) {
                $css .= $config['custom_css'];
            }

            $css .= "</style>";
            echo $css;
        });

        // 2. Affichage du Logo
        add_action('theme_logo', function() use ($config) {
            if (!empty($config['logo'])) {
                echo '<img src="'.asset('storage/'.$config['logo']).'" alt="Logo" style="max-height: 40px;">';
            } else {
                echo '⚡ Jangal CMS';
            }
        });

        // 3. Liens Sidebar
        add_action('admin_sidebar_links', function() {
            if (\Illuminate\Support\Facades\Route::has('admin.theme-options.index')) {
                $active = request()->routeIs('admin.theme-options.*') ? 'active' : '';
                echo '<a href="' . route('admin.theme-options.index') . '" class="' . $active . '">🎨 Charte graphique</a>';
            }
        });

        $this->loadViewsFrom(base_path('platform/plugins/theme-options/views'), 'theme-options');
        $this->loadRoutesFrom(base_path('platform/plugins/theme-options/routes.php'));
    }
}