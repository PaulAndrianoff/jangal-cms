<?php

namespace Platform\Plugins\ThemeOptions\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OptionsController extends Controller
{
    protected function getPath($file) {
        return base_path('platform/plugins/theme-options/' . $file);
    }

    // Page des options
    public function index()
    {
        $schema = json_decode(File::get($this->getPath('schema.json')), true) ?? [];
        $config = json_decode(File::get($this->getPath('config.json')), true) ?? [];
        return view('theme-options::settings', compact('schema', 'config'));
    }

    // Sauvegarde des options
    public function update(Request $request)
    {
        $schema = json_decode(File::get($this->getPath('schema.json')), true) ?? [];
        $config = json_decode(File::get($this->getPath('config.json')), true) ?? [];

        foreach ($schema as $field) {
            $name = $field['name'];

            if ($field['type'] === 'file') {
                if ($request->hasFile($name)) {
                    if (!empty($config[$name]) && File::exists(storage_path('app/public/' . $config[$name]))) {
                        File::delete(storage_path('app/public/' . $config[$name]));
                    }
                    $config[$name] = $request->file($name)->store('theme-options', 'public');
                }
            } else {
                if ($request->has($name)) {
                    $config[$name] = $request->input($name);
                }
            }
        }

        File::put($this->getPath('config.json'), json_encode($config, JSON_PRETTY_PRINT));
        return back()->with('success', 'Charte graphique mise à jour !');
    }

    // Page d'édition du schéma (Ajout/suppression de champs)
    public function editSchema()
    {
        $schema = json_decode(File::get($this->getPath('schema.json')), true) ?? [];
        return view('theme-options::schema', compact('schema'));
    }

    // Sauvegarde du schéma
    public function updateSchema(Request $request)
    {
        $fields = [];
        if ($request->has('fields')) {
            foreach ($request->fields as $field) {
                if (empty($field['name']) || empty($field['label'])) continue;
                
                $newField = [
                    'name' => \Illuminate\Support\Str::slug($field['name'], '_'),
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'default' => $field['default'] ?? ''
                ];

                if ($field['type'] === 'select' && !empty($field['options'])) {
                    $newField['options'] = array_map('trim', explode(',', $field['options']));
                }

                $fields[] = $newField;
            }
        }

        File::put($this->getPath('schema.json'), json_encode($fields, JSON_PRETTY_PRINT));
        return back()->with('success', 'Structure des champs mise à jour !');
    }
}