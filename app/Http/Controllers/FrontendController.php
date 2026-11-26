<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use App\Models\Tag;
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

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()->where('status', 'published')->paginate(9);
        return view('theme::tag', compact('posts', 'tag'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $posts = Post::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })->paginate(9);

        return view('theme::search', compact('posts', 'query'));
    }
}
