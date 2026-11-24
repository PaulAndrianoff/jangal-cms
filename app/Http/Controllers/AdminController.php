<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Core\Plugins\PluginManager;
use App\Core\Themes\ThemeManager;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $pluginManager;
    protected $themeManager;

    public function __construct(PluginManager $pluginManager, ThemeManager $themeManager)
    {
        $this->pluginManager = $pluginManager;
        $this->themeManager = $themeManager;
    }

    public function dashboard()
    {
        $plugins = $this->pluginManager->getAvailablePlugins();
        $activePlugins = DB::table('plugins')->where('is_active', true)->pluck('name')->toArray();

        $themes = $this->themeManager->getAvailableThemes();
        $activeTheme = $this->themeManager->getActiveTheme();

        $stats = [
            'posts' => \App\Models\Post::count(),
            'pages' => \App\Models\Page::count(),
            'users' => \App\Models\User::count(),
            'media' => \App\Models\Media::count(),
        ];

        return view('admin.dashboard', compact('stats', 'plugins', 'activePlugins', 'themes', 'activeTheme'));
    }

    public function togglePlugin($slug)
    {
        $plugin = DB::table('plugins')->where('name', $slug)->first();

        if ($plugin && $plugin->is_active) {
            $this->pluginManager->deactivate($slug);
            return back()->with('success', "Plugin [{$slug}] désactivé.");
        }

        $this->pluginManager->activate($slug);
        return back()->with('success', "Plugin [{$slug}] activé.");
    }

    public function activateTheme($slug)
    {
        $this->themeManager->activate($slug);
        return back()->with('success', "Thème [{$slug}] activé.");
    }

    public function users()
    {
        $users = \App\Models\User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }
}
