<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Core\Themes\ThemeManager;
use Illuminate\Http\Request;
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
}