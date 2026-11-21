<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'tags'])->where('status', 'published')->orderBy('created_at', 'desc')->paginate(9);

        return view('theme::welcome', compact('posts'));
    }

    public function show(Post $post)
    {
        if ($post->status !== 'published') {
            abort(404);
        }
        $recentPosts = Post::where('status', 'published')->where('id', '!=', $post->id)->latest()->take(3)->get();

        return view('theme::single', compact('post', 'recentPosts')); // <-- Ajout de theme::
    }

    public function showPage(Page $page)
    {
        if ($page->status !== 'published') {
            abort(404);
        }

        return view('theme::page', compact('page'));
    }
}
