<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\Themes\ThemeManager;

class ThemeList extends Command
{
    protected $signature = 'theme:list';
    protected $description = 'Liste tous les thèmes';

    public function handle(ThemeManager $manager)
    {
        $available = $manager->getAvailableThemes();
        $active = $manager->getActiveTheme();

        $rows = [];
        foreach ($available as $theme) {
            $isActive = ($active && $active->name === $theme['slug']) ? '✅ Actif' : '❌ Inactif';
            $rows[] = [
                $theme['name'] ?? $theme['slug'],
                $theme['version'] ?? '1.0.0',
                $isActive
            ];
        }

        $this->table(['Nom', 'Version', 'Statut'], $rows);
    }
}