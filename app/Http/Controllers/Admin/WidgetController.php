<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Widget;

class WidgetController extends Controller
{
    public function index()
    {
        // Zones définies par le thème
        $areas = ['header' => 'En-tête', 'sidebar' => 'Barre latérale', 'footer' => 'Pied de page'];
        
        // Widgets disponibles (enregistrés par les plugins)
        $availableWidgets = app('widget')->getAvailableWidgets();
        
        // Widgets actuellement assignés
        $activeWidgets = Widget::orderBy('order')->get();

        return view('admin.widgets.index', compact('areas', 'availableWidgets', 'activeWidgets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area' => 'required|string',
            'type' => 'required|string'
        ]);

        Widget::create([
            'area' => $request->area,
            'type' => $request->type,
            'settings' => []
        ]);

        return back()->with('success', 'Widget ajouté avec succès.');
    }

    public function destroy(Widget $widget)
    {
        $widget->delete();
        return back()->with('success', 'Widget supprimé.');
    }

        public function edit(Widget $widget)
    {
        $availableWidgets = app('widget')->getAvailableWidgets();
        
        // On vérifie si ce widget a une vue de configuration
        if (empty($availableWidgets[$widget->type]['settings_view'])) {
            return back()->with('error', 'Ce widget n\'a pas de paramètres configurables.');
        }

        $settingsView = $availableWidgets[$widget->type]['settings_view'];
        return view($settingsView, compact('widget'));
    }

    public function update(Request $request, Widget $widget)
    {
        // On sauvegarde les données du formulaire dans la colonne JSON 'settings'
        $widget->update([
            'settings' => $request->settings
        ]);

        return redirect()->route('admin.widgets.index')->with('success', 'Paramètres du widget mis à jour.');
    }
}