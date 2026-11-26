@extends('theme::master')
@section('title', 'Portfolio - Minimalist')

@section('content')
    <!-- Hero -->
    <div class="container text-center py-5 my-5">
        <h1 class="display-3 mb-4" style="max-width: 900px; margin: 0 auto; line-height: 1.1;">Capturer l'instant, raconter une histoire.</h1>
        <p class="lead-text mx-auto" style="max-width: 700px;">Photographe & Directeur Artistique basé à Paris. Je transforme vos idées en visuels inoubliables.</p>
    </div>

    <!-- Grille de projets (Masonry) -->
    <div class="portfolio-grid">
        @foreach($posts as $post)
            <a href="{{ route('post.show', $post->slug) }}" class="portfolio-item text-decoration-none">
                @if($post->cover)
                    <img src="{{ asset('storage/' . $post->cover) }}" alt="{{ $post->title }}">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">Sans image</div>
                @endif
                
                <div class="portfolio-text">
                    <h3 class="serif mb-1">{{ $post->title }}</h3>
                    <p class="mb-0 text-uppercase small" style="letter-spacing: 1px;">{{ $post->created_at->format('M Y') }}</p>
                </div>
            </a>
        @endforeach
    </div>
@endsection