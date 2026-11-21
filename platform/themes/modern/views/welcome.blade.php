@extends('theme::master')

@section('title', 'Accueil - Jangal CMS')

@section('content')
    <div class="hero">
        <h1 class="display-4 fw-bold">Bienvenue sur Jangal CMS</h1>
        <p class="lead">Le meilleur CMS propulsé par Laravel</p>
    </div>

    <div class="container">
        <div class="row">
            @forelse($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 post-card">
                        <!-- Lien vers l'article -->
                        <a href="{{ route('post.show', $post->slug) }}">
                            @if($post->cover)
                                <img src="{{ asset('storage/' . $post->cover) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $post->title }}">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">Pas d'image</div>
                            @endif
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none text-dark">{{ $post->title }}</a>
                            </h5>
                            <p class="text-muted small mb-2">Par {{ $post->author->name }} le {{ $post->created_at->format('d/m/Y') }}</p>
                            
                            <!-- Affichage des tags -->
                            <div class="mb-3">
                                @foreach($post->tags as $tag)
                                    <span class="badge bg-primary">#{{ $tag->name }}</span>
                                @endforeach
                            </div>

                            <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="{{ route('post.show', $post->slug) }}" class="btn btn-primary w-100">Lire la suite</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <h3 class="text-muted">Aucun article publié pour le moment.</h3>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    </div>
@endsection