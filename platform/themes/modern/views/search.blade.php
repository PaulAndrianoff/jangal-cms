@extends('theme::master')

@section('title', 'Résultats pour : ' . $query)

@section('content')
    <div class="container">
        <!-- En-tête de l'archive -->
        <div class="text-center mb-5 py-4">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2">Archive</span>
            <h1 class="hero-title display-5">Articles tagués : <span class="text-primary">Résultats pour : '{{ $query }}'</span></h1>
        </div>

        <div class="row g-5">
            <!-- Grille d'articles -->
            <div class="col-lg-8">
                <div class="row g-4">
                    @forelse($posts as $post)
                        <div class="col-md-6">
                            <div class="card post-card h-100">
                                @if($post->cover)
                                    <img src="{{ asset('storage/' . $post->cover) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3">
                                        <a href="{{ route('post.show', $post->slug) }}" class="text-dark text-decoration-none">{{ $post->title }}</a>
                                    </h5>
                                    <p class="text-muted small">{{ Str::limit(strip_tags($post->content), 80) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <h4 class="text-muted">Aucun article ne correspond à ce tag.</h4>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $posts->links() }}</div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                {!! render_widget_area('sidebar') !!}
            </div>
        </div>
    </div>
@endsection