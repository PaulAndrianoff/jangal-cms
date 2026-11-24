<?php

namespace Platform\Plugins\ActivityLogger\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Platform\Plugins\ActivityLogger\Models\ActivityLog;
use App\Models\Post;
use App\Models\Page;
use App\Models\User;
use App\Models\Media;

class PluginServiceProvider extends ServiceProvider
{
    protected function isEnabled($action)
    {
        $configPath = base_path('platform/plugins/activity-logger/config.json');
        if (!File::exists($configPath)) return true;

        $config = json_decode(File::get($configPath), true);
        return $config[$action] ?? false;
    }

    protected function log($action, $module, $description)
    {
        // On log uniquement si on est dans l'admin
        if (request()->is('admin*') && Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => request()->ip(),
            ]);
        }
    }

    public function boot()
    {
        // 1. LOGIN / LOGOUT
        Event::listen('Illuminate\Auth\Events\Login', function ($event) {
            if ($this->isEnabled('log_auth')) {
                $this->log('login', 'Auth', "S'est connecté au panneau d'administration");
            }
        });
        Event::listen('Illuminate\Auth\Events\Logout', function ($event) {
            if ($this->isEnabled('log_auth') && $event->user) {
                $this->log('logout', 'Auth', "S'est déconnecté");
            }
        });

        // 2. POSTS
        if ($this->isEnabled('log_posts')) {
            Post::created(fn($p) => $this->log('created', 'Post', "A créé l'article : {$p->title}"));
            Post::updated(fn($p) => $this->log('updated', 'Post', "A modifié l'article : {$p->title}"));
            Post::deleted(fn($p) => $this->log('deleted', 'Post', "A supprimé l'article : {$p->title}"));
        }

        // 3. PAGES
        if ($this->isEnabled('log_pages')) {
            Page::created(fn($p) => $this->log('created', 'Page', "A créé la page : {$p->title}"));
            Page::updated(fn($p) => $this->log('updated', 'Page', "A modifié la page : {$p->title}"));
            Page::deleted(fn($p) => $this->log('deleted', 'Page', "A supprimé la page : {$p->title}"));
        }

        // 4. USERS
        if ($this->isEnabled('log_users')) {
            User::created(fn($u) => $this->log('created', 'User', "A créé l'utilisateur : {$u->name}"));
            User::updated(fn($u) => $this->log('updated', 'User', "A modifié l'utilisateur : {$u->name}"));
            User::deleted(fn($u) => $this->log('deleted', 'User', "A supprimé l'utilisateur : {$u->name}"));
        }

        // 5. MEDIA
        if ($this->isEnabled('log_media')) {
            Media::created(fn($m) => $this->log('created', 'Media', "A uploadé le média : {$m->name}"));
            Media::deleted(fn($m) => $this->log('deleted', 'Media', "A supprimé le média : {$m->name}"));
        }

        // Chargement des routes du plugin
        $this->loadViewsFrom(base_path('platform/plugins/activity-logger/views'), 'activity-logger');

        // Chargement des routes du plugin
        $this->loadRoutesFrom(base_path('platform/plugins/activity-logger/routes.php'));

        add_action('admin_sidebar_links', function() {
            if (\Illuminate\Support\Facades\Route::has('admin.logs.index')) {
                $active = request()->routeIs('admin.logs.*') ? 'active' : '';
                echo '<li class="nav-item mb-1">
                        <a href="' . route('admin.logs.index') . '" class="nav-link sidebar-link ' . $active . '">
                            <i class="bi bi-journal-text"></i> Journal d\'activité
                        </a>
                      </li>';
            }
        });
    }
}
