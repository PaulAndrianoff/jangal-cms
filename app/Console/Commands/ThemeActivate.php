<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\Themes\ThemeManager;

class ThemeActivate extends Command
{
    protected $signature = 'theme:activate {slug}';
    protected $description = 'Active un thème';

    public function handle(ThemeManager $manager)
    {
        $slug = $this->argument('slug');
        $manager->activate($slug);
        $this->info("Le thème [{$slug}] a été activé avec succès.");
    }
}