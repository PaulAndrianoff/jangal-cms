<?php

namespace App\Core\Widgets;

use App\Models\Widget;

class WidgetManager
{
    protected $registeredWidgets = [];

        // Permet aux plugins d'enregistrer un widget
    public function register($type, $name, callable $renderCallback, $settingsView = null)
    {
        $this->registeredWidgets[$type] = [
            'name' => $name,
            'render' => $renderCallback,
            'settings_view' => $settingsView // <-- Nouveau
        ];
    }

    // Récupère la liste des widgets disponibles (pour l'admin)
    public function getAvailableWidgets()
    {
        return $this->registeredWidgets;
    }

    // Affiche tous les widgets d'une zone spécifique
    public function renderArea($area)
    {
        $widgets = Widget::where('area', $area)->orderBy('order')->get();
        $html = '';

        foreach ($widgets as $widget) {
            if (isset($this->registeredWidgets[$widget->type])) {
                $callback = $this->registeredWidgets[$widget->type]['render'];
                // On passe les settings du widget à la fonction de rendu
                $html .= $callback($widget->settings ?? []);
            }
        }

        return $html;
    }
}
