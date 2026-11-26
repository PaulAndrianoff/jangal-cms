<?php

namespace Platform\Plugins\SearchWidget\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $widgetManager = app('widget');

        // Enregistrement du widget "search_bar"
        $widgetManager->register('search_bar', 'Barre de recherche', function ($settings) {
            // On récupère l'URL de la route de recherche (ou on met un fallback)
            $actionUrl = \Illuminate\Support\Facades\Route::has('search') ? route('search') : '/search';
            
            // On génère un joli formulaire avec Bootstrap
            return '
            <div class="card mb-4 shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">🔍 Rechercher</h5>
                    <form action="' . $actionUrl . '" method="GET" class="d-flex gap-2">
                        <input type="text" name="q" class="form-control" placeholder="Mot-clé..." required>
                        <button type="submit" class="btn btn-primary px-3">
                            Go
                        </button>
                    </form>
                </div>
            </div>';
        });
    }
}