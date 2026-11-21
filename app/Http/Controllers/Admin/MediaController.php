<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::orderBy('created_at', 'desc')->paginate(24);
        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('media', $fileName, 'public');

        Media::create([
            'user_id' => Auth::id(),
            'name' => $originalName,
            'file_name' => $fileName,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
        ]);

        return back()->with('success', 'Fichier ajouté à la médiathèque.');
    }

    public function destroy(Media $medium)
    {
        Storage::disk('public')->delete($medium->path);
        $medium->delete();
        return back()->with('success', 'Média supprimé.');
    }

    // Endpoint pour l'upload via TinyMCE
    public function uploadTinyMCE(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media', $fileName, 'public');

            Media::create([
                'user_id' => Auth::id(),
                'name' => $originalName,
                'file_name' => $fileName,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
            ]);

            return response()->json(['location' => asset('storage/' . $path)]);
        }
        return response()->json(['error' => 'Upload failed'], 400);
    }

    // Méthode pour récupérer uniquement les images en JSON
    public function list()
    {
        $images = Media::where('mime_type', 'like', 'image/%')->orderBy('created_at', 'desc')->get();
        return response()->json($images);
    }
}
