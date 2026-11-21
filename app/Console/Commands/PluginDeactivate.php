<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\Plugins\PluginManager;

class PluginDeactivate extends Command
{
    protected $signature = 'plugin:deactivate {slug}';
    protected $description = 'Désactive un plugin';

    public function handle(PluginManager $manager)
    {
        $slug = $this->argument('slug');
        $manager->deactivate($slug);
        $this->info("Le plugin [{$slug}] a été désactivé.");
    }
}