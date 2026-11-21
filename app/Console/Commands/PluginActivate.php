<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\Plugins\PluginManager;

class PluginActivate extends Command
{
    protected $signature = 'plugin:activate {slug}';
    protected $description = 'Active un plugin';

    public function handle(PluginManager $manager)
    {
        $slug = $this->argument('slug');
        $manager->activate($slug);
        $this->info("Le plugin [{$slug}] a été activé.");
    }
}