<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\Plugins\PluginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

    // Afficher l'éditeur JSON
    public function editJson($slug, Request $request)
    {
        $pluginPath = base_path("platform/plugins/{$slug}");

        // Liste tous les fichiers .json à la racine du plugin
        $jsonFiles = [];
        foreach (glob($pluginPath . '/*.json') as $file) {
            $jsonFiles[] = basename($file);
        }

        if (empty($jsonFiles)) {
            return back()->with('error', 'Aucun fichier JSON trouvé dans ce plugin.');
        }

        // Fichier à éditer (par défaut plugin.json)
        $currentFile = $request->query('file', 'plugin.json');

        // Sécurité : on s'assure que le fichier demandé est bien dans la liste
        if (!in_array($currentFile, $jsonFiles)) {
            $currentFile = $jsonFiles[0];
        }

        $path = $pluginPath . '/' . $currentFile;
        $content = File::get($path);

        return view('admin.plugins.json', compact('slug', 'jsonFiles', 'currentFile', 'content'));
    }

    // Sauvegarder le fichier JSON
    public function updateJson(Request $request, $slug)
    {
        $request->validate([
            'file_name' => 'required|string',
            'content' => 'required|string'
        ]);

        $fileName = $request->file_name;

        // Sécurité anti "Directory Traversal" (ex: essayer d'éditer ../../../.env)
        if (str_contains($fileName, '/') || str_contains($fileName, '\\') || !str_ends_with($fileName, '.json')) {
            abort(403, 'Nom de fichier invalide.');
        }

        // Validation de la syntaxe JSON
        json_decode($request->content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Erreur de syntaxe JSON : ' . json_last_error_msg() . '. Veuillez corriger avant de sauvegarder.');
        }

        $path = base_path("platform/plugins/{$slug}/{$fileName}");

        if (!File::exists($path)) {
            return back()->with('error', 'Fichier introuvable.');
        }

        File::put($path, $request->content);

        return redirect()->route('admin.plugins.json', ['slug' => $slug, 'file' => $fileName])
            ->with('success', 'Fichier ' . $fileName . ' mis à jour avec succès !');
    }
}
