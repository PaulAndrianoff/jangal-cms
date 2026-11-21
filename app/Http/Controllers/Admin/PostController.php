<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        // On récupère les articles avec l'auteur, du plus récent au plus ancien
        $posts = Post::with('author')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'cover' => 'nullable|image|max:2048', // Max 2MB
            'media_file' => 'nullable|file|mimes:mp4,pdf|max:10240', // Max 10MB
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . uniqid(); // Assure l'unicité
        $data['user_id'] = Auth::id();

        // Gestion de l'image de couverture
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Gestion des médias (vidéo/pdf)
        if ($request->hasFile('media_file')) {
            $data['media_file'] = $request->file('media_file')->store('media', 'public');
        }

        $post = Post::create($data); 
        $this->syncTags($post, $request->tags);
        
        return redirect()->route('admin.posts.index')->with('success', 'Article créé avec succès.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'cover' => 'nullable|image|max:2048',
            'media_file' => 'nullable|file|mimes:mp4,pdf|max:10240',
        ]);

        $data = $request->all();

        // Nouvelle image de couverture
        if ($request->hasFile('cover')) {
            if ($post->cover) Storage::disk('public')->delete($post->cover); // Supprime l'ancienne
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Nouveau média
        if ($request->hasFile('media_file')) {
            if ($post->media_file) Storage::disk('public')->delete($post->media_file);
            $data['media_file'] = $request->file('media_file')->store('media', 'public');
        }

        $post->update($data);
        $this->syncTags($post, $request->tags);

        return redirect()->route('admin.posts.index')->with('success', 'Article mis à jour.');
    }

    public function destroy(Post $post)
    {
        // Supprimer les fichiers physiques
        if ($post->cover) Storage::disk('public')->delete($post->cover);
        if ($post->media_file) Storage::disk('public')->delete($post->media_file);

        $post->delete();

        return back()->with('success', 'Article supprimé.');
    }

    private function syncTags($post, $tagsString)
    {
        $tagIds = [];
        if ($tagsString) {
            // Séparer par des virgules et nettoyer les espaces
            $tags = array_filter(array_map('trim', explode(',', $tagsString)));

            foreach ($tags as $tagName) {
                // Trouver le tag ou le créer s'il n'existe pas
                $tag = \App\Models\Tag::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }
        // Attache uniquement les tags sélectionnés (et détache les autres)
        $post->tags()->sync($tagIds);
    }
}
