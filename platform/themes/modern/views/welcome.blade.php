@extends('theme::master')

@section('title', 'Accueil - Blog Moderne')

@section('content')
    <div class="container">
        <div class="row g-5">
            
            <!-- Colonne Principale (Articles) -->
            <div class="col-lg-8">
                <!-- Article à la une (Le premier de la liste) -->
                @if($posts->count() > 0)
                    @php $hero = $posts->first(); @endphp
                    <a href="{{ route('post.show', $hero->slug) }}" class="text-decoration-none d-block mb-5">
                        <div class="card post-card p-3 p-md-4">
                            <div class="row align-items-center g-4">
                                <div class="col-md-6">
                                    @if($hero->cover)
                                        <img src="{{ asset('storage/' . $hero->cover) }}" class="img-fluid rounded-4 w-100" style="height: 300px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">À la une</span>
                                    <h2 class="hero-title fs-2 mb-3 text-dark">{{ $hero->title }}</h2>
                                    <p class="text-muted mb-4">{{ Str::limit(strip_tags($hero->content), 120) }}</p>
                                    <div class="d-flex align-items-center gap-2 text-muted small">
                                        <i class="bi bi-person-circle"></i> {{ $hero->author->name }} • {{ $hero->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif

                <!-- Grille des autres articles -->
                <h4 class="fw-bold mb-4">Derniers articles</h4>
                <div class="row g-4">
                    @foreach($posts->skip(1) as $post)
                        <div class="col-md-6">
                            <div class="card post-card h-100">
                                <a href="{{ route('post.show', $post->slug) }}">
                                    @if($post->cover)
                                        <img src="{{ asset('storage/' . $post->cover) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    @endif
                                </a>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="mb-2">
                                        @foreach($post->tags->take(2) as $tag)
                                            <span class="text-primary small fw-bold">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                    <h5 class="fw-bold mb-3">
                                        <a href="{{ route('post.show', $post->slug) }}" class="text-dark text-decoration-none">{{ Str::limit($post->title, 50) }}</a>
                                    </h5>
                                    <div class="mt-auto d-flex align-items-center justify-content-between pt-3 border-top">
                                        <small class="text-muted">{{ $post->created_at->format('d M Y') }}</small>
                                        <a href="{{ route('post.show', $post->slug) }}" class="btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar (Colonne de droite) -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    {!! render_widget_area('sidebar') !!}
                    
                    <!-- Widget de secours si la sidebar est vide -->
                    <div class="sidebar-widget text-center">
                        <h5 class="fw-bold">Inscrivez-vous</h5>
                        <p class="text-muted small mb-3">Recevez les derniers articles par email.</p>
                        <input type="email" class="form-control mb-2" placeholder="Votre email">
                        <button class="btn btn-primary w-100">S'abonner</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection