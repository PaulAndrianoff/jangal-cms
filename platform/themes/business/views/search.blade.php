@extends('theme::master')

@section('title', 'Résultats pour : ' . $query)

@section('content')
    <!-- Hero de l'archive -->
    <header class="hero py-5" style="padding: 80px 0 !important;">
        <div class="container text-center">
            <h1 class="display-4 fw-bold text-white">"Résultats pour : '{{ $query }}'"</h1>
            <p class="text-light opacity-75 mt-3">Retrouvez toutes nos actualités liées à ce sujet.</p>
        </div>
    </header>

    <!-- Articles -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-4">
                        <div class="card card-post h-100">
                            @if($post->cover)
                                <img src="{{ asset('storage/' . $post->cover) }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                            @endif
                            <div class="card-body p-4">
                                <h4 class="h5 fw-bold">{{ $post->title }}</h4>
                                <p class="text-muted small mt-3">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                                <a href="{{ route('post.show', $post->slug) }}" class="fw-bold text-decoration-none" style="color: var(--accent-color);">Lire l'article</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted">Aucun article pour le moment.</h4>
                    </div>
                @endforelse
            </div>
            <div class="mt-5">{{ $posts->links() }}</div>
        </div>
    </section>
@endsection