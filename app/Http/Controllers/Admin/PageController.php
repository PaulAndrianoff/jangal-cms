<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Si le slug existe déjà, on ajoute un identifiant unique
        if (Page::where('slug', $data['slug'])->exists()) {
            $data['slug'] .= '-' . uniqid();
        }

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page créée avec succès.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->all();
        
        // On ne met à jour le slug que si le titre a changé
        if ($page->title !== $request->title) {
            $data['slug'] = Str::slug($request->title);
            if (Page::where('slug', $data['slug'])->where('id', '!=', $page->id)->exists()) {
                $data['slug'] .= '-' . uniqid();
            }
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page mise à jour.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page supprimée.');
    }
}