<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\Plugins\PluginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PluginController extends Controller
{
    protected $pluginManager;

    public function __construct(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }

    public function index()
    {
        $plugins = $this->pluginManager->getAvailablePlugins();
        $activePlugins = DB::table('plugins')->where('is_active', true)->pluck('name')->toArray();

        return view('admin.plugins.index', compact('plugins', 'activePlugins'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'plugin_zip' => 'required|file|mimes:zip|max:20480'
        ]);

        if ($this->pluginManager->uploadPlugin($request->file('plugin_zip'))) {
            return back()->with('success', 'Plugin installé avec succès !');
        }
        return back()->with('error', 'Erreur lors de l\'extraction du fichier ZIP.');
    }

    public function edit($slug)
    {
        $plugins = $this->pluginManager->getAvailablePlugins();
        $plugin = collect($plugins)->firstWhere('slug', $slug);

        if (!$plugin) {
            return redirect()->route('admin.plugins.index')->with('error', 'Plugin introuvable.');
        }

        return view('admin.plugins.edit', compact('plugin', 'slug'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:50',
        ]);

        if ($this->pluginManager->updatePluginManifest($slug, $request->all())) {
            return redirect()->route('admin.plugins.index')->with('success', 'Informations du plugin mises à jour.');
        }
        return back()->with('error', 'Erreur lors de la mise à jour.');
    }

    public function toggle($slug)
    {
        $plugin = DB::table('plugins')->where('name', $slug)->first();

        if ($plugin && $plugin->is_active) {
            $this->pluginManager->deactivate($slug);
            return back()->with('success', "Plugin [{$slug}] désactivé.");
        }

        $this->pluginManager->activate($slug);
        return back()->with('success', "Plugin [{$slug}] activé.");
    }

    public function destroy($slug)
    {
        // On s'assure qu'il est désactivé avant suppression
        $this->pluginManager->deactivate($slug);
        
        if ($this->pluginManager->deletePlugin($slug)) {
            return back()->with('success', "Plugin [{$slug}] supprimé définitivement.");
        }
        return back()->with('error', "Impossible de supprimer le plugin.");
    }
}