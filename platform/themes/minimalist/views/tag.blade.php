@extends('theme::layouts.master')

@section('title', 'Tag : ' . $tag->name)

@section('content')
    <div class="container text-center py-5 my-5">
        <h1 class="display-3 mb-4">Tag : #{{ $tag->name }}</h1>
        <p class="lead-text">Toutes les histoires liées à ce sujet.</p>
    </div>

    <div class="portfolio-grid">
        @forelse($posts as $post)
            <a href="{{ route('post.show', $post->slug) }}" class="portfolio-item text-decoration-none">
                @if($post->cover)
                    <img src="{{ asset('storage/' . $post->cover) }}" alt="{{ $post->title }}">
                @endif
                <div class="portfolio-text">
                    <h3 class="serif mb-1">{{ $post->title }}</h3>
                    <p class="mb-0 text-uppercase small" style="letter-spacing: 1px;">{{ $post->created_at->format('M Y') }}</p>
                </div>
            </a>
        @empty
            <div class="w-100 text-center py-5 text-muted">Aucun article pour ce tag.</div>
        @endforelse
    </div>
    
    <div class="container mt-5 d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
@endsection