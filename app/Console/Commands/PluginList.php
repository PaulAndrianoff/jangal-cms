<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\Plugins\PluginManager;
use Illuminate\Support\Facades\DB;

class PluginList extends Command
{
    protected $signature = 'plugin:list';
    protected $description = 'Liste tous les plugins';

    public function handle(PluginManager $manager)
    {
        $available = $manager->getAvailablePlugins();
        $active = DB::table('plugins')->where('is_active', true)->pluck('name')->toArray();

        $rows = [];
        foreach ($available as $plugin) {
            $rows[] = [
                $plugin['name'] ?? $plugin['slug'],
                $plugin['version'] ?? '1.0.0',
                in_array($plugin['slug'], $active) ? '✅ Actif' : '❌ Inactif'
            ];
        }

        $this->table(['Nom', 'Version', 'Statut'], $rows);
    }
}