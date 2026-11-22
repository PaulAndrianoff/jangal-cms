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
}