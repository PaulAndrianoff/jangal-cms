<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\Themes\ThemeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    protected $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    public function index()
    {
        $themes = $this->themeManager->getAvailableThemes();
        $activeTheme = $this->themeManager->getActiveTheme();
        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:20480' // Max 20Mo
        ]);

        if ($this->themeManager->uploadTheme($request->file('theme_zip'))) {
            return back()->with('success', 'Thème installé avec succès !');
        }
        return back()->with('error', 'Erreur lors de l\'extraction du fichier ZIP.');
    }

    public function activate($slug)
    {
        $this->themeManager->activate($slug);
        return back()->with('success', "Thème [{$slug}] activé avec succès.");
    }

    public function edit($slug)
    {
        $themes = $this->themeManager->getAvailableThemes();
        $theme = collect($themes)->firstWhere('slug', $slug);

        if (!$theme) {
            return redirect()->route('admin.themes.index')->with('error', 'Thème introuvable.');
        }

        return view('admin.themes.edit', compact('theme'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:50',
        ]);

        if ($this->themeManager->updateThemeManifest($slug, $request->all())) {
            return redirect()->route('admin.themes.index')->with('success', 'Informations du thème mises à jour.');
        }
        return back()->with('error', 'Erreur lors de la mise à jour.');
    }

    public function destroy($slug)
    {
        $result = $this->themeManager->deleteTheme($slug);

        if ($result === true) {
            return back()->with('success', "Thème [{$slug}] supprimé définitivement.");
        }

        // Si ce n'est pas 'true', c'est un message d'erreur de sécurité
        return back()->with('error', $result);
    }

    public function files($slug)
    {
        $themePath = base_path('platform/themes/' . $slug);
        if (!File::exists($themePath)) {
            return back()->with('error', 'Dossier du thème introuvable.');
        }

        // On récupère tous les fichiers récursivement
        $files = File::allFiles($themePath);

        // On formate les chemins pour l'affichage
        $fileList = [];
        foreach ($files as $file) {
            $relativePath = str_replace($themePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $fileList[] = str_replace('\\', '/', $relativePath);
        }

        sort($fileList);

        return view('admin.themes.files', compact('slug', 'fileList'));
    }

    // 2. Afficher l'éditeur de fichier
    public function editFile($slug, Request $request)
    {
        $file = $request->query('file');
        $themePath = realpath(base_path('platform/themes/' . $slug));
        $filePath = realpath($themePath . '/' . $file);

        // SÉCURITÉ : Vérifier que le fichier demandé est bien DANS le dossier du thème
        if (!str_starts_with($filePath, $themePath)) {
            abort(403, 'Tentative de sortie du dossier du thème bloquée.');
        }

        if (!File::exists($filePath)) {
            return back()->with('error', 'Fichier introuvable.');
        }

        $content = File::get($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return view('admin.themes.editor', compact('slug', 'file', 'content', 'extension'));
    }

    // 3. Sauvegarder les modifications
    public function updateFile(Request $request, $slug)
    {
        $file = $request->file;
        $themePath = realpath(base_path('platform/themes/' . $slug));
        $filePath = realpath($themePath . '/' . $file);

        // SÉCURITÉ : Même vérification
        if (!str_starts_with($filePath, $themePath)) {
            abort(403, 'Tentative de modification hors du thème bloquée.');
        }

        File::put($filePath, $request->content);

        return redirect()->route('admin.themes.editFile', ['slug' => $slug, 'file' => $file])
            ->with('success', 'Fichier modifié avec succès !');
    }
}
