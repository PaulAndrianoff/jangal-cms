<?php

namespace Platform\Plugins\BasicWidgets\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Page;
use App\Models\Tag;

class PluginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $widgetManager = app('widget');

        // 1. WIDGET MENU (Navigation)
        $widgetManager->register('menu', 'Menu de navigation', function ($settings) {
            // On récupère les liens depuis les paramètres, ou un tableau vide
            $items = $settings['items'] ?? [];

            // Si l'utilisateur n'a rien configuré, on fait le fallback (liste des pages)
            if (empty($items)) {
                $pages = \App\Models\Page::where('status', 'published')->get();
                foreach ($pages as $page) {
                    $items[] = ['label' => $page->title, 'url' => route('page.show', $page->slug)];
                }
            }

            $html = '<div class="card mb-4 shadow-sm"><div class="card-body">';
            $html .= '<ul class="list-unstyled mb-0">';
            foreach ($items as $item) {
                if (!empty($item['label']) && !empty($item['url'])) {
                    $html .= '<li class="mb-2"><a href="' . $item['url'] . '" class="text-decoration-none text-dark fw-semibold">' . $item['label'] . '</a></li>';
                }
            }
            $html .= '</ul></div></div>';
            return $html;
        }, 'admin.widgets.settings.menu');

        // 2. WIDGET TAGS
        $widgetManager->register('tag_cloud', 'Nuage de Tags', function ($settings) {
            $tags = Tag::has('posts')->get();
            $html = '<div class="card mb-4 shadow-sm"><div class="card-header bg-white fw-bold">Tags</div><div class="card-body">';
            foreach ($tags as $tag) {
                // Lien vers une future page de recherche par tag (qu'on créera plus tard)
                $html .= '<a href="/tag/' . $tag->slug . '" class="badge bg-primary text-decoration-none me-1 mb-1">#' . $tag->name . '</a>';
            }
            $html .= '</div></div>';
            return $html;
        });

        // 3. WIDGET FORMULAIRE (Contact)
        $widgetManager->register('contact_form', 'Formulaire de contact', function ($settings) {
            return '
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white fw-bold">Contactez-nous</div>
                <div class="card-body">
                    <form action="#" method="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <div class="mb-2"><input type="text" class="form-control" placeholder="Votre nom" required></div>
                        <div class="mb-2"><input type="email" class="form-control" placeholder="Votre email" required></div>
                        <div class="mb-2"><textarea class="form-control" rows="3" placeholder="Message" required></textarea></div>
                        <button class="btn btn-primary w-100">Envoyer</button>
                    </form>
                </div>
            </div>';
        });

        // 4. WIDGET SLIDER (Carrousel d'images basiques)
        $widgetManager->register('slider', 'Slider d\'images', function ($settings) {
            // Pour l'exemple, on met des images statiques. 
            // Dans un vrai CMS, les images viendraient des settings du widget.
            return '
            <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
              <div class="carousel-inner rounded shadow-sm">
                <div class="carousel-item active">
                  <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:200px;">Slide 1</div>
                </div>
                <div class="carousel-item">
                  <div class="bg-dark text-white d-flex align-items-center justify-content-center" style="height:200px;">Slide 2</div>
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
              </button>
            </div>';
        });
    }
}
