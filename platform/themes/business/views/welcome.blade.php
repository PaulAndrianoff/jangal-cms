@extends('theme::master')
@section('title', 'Accueil - Business Pro')

@section('content')
    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <span class="badge bg-accent bg-opacity-25 text-white px-3 py-2 rounded-pill mb-3">Nouveau : Découvrez nos solutions</span>
            <h1 class="mb-4">Propulsez votre entreprise <br> vers de nouveaux sommets.</h1>
            <p class="lead mb-5 mx-auto text-light opacity-75" style="max-width: 700px;">Nous accompagnons les entreprises modernes dans leur transformation digitale avec des solutions sur mesure et performantes.</p>
            <a href="#articles" class="btn btn-accent btn-lg me-2">Nos réalisations</a>
            <a href="/contact" class="btn btn-outline-light btn-lg rounded-pill px-4">Nous contacter</a>
        </div>
    </section>

    <!-- Blog Section -->
    <section id="articles" class="py-5" style="margin-top: -50px;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Dernières actualités</h2>
                <div class="mx-auto bg-primary mt-2" style="width: 60px; height: 3px; background-color: var(--accent-color) !important;"></div>
            </div>
            
            <div class="row g-4">
                @foreach($posts as $post)
                    <div class="col-md-4">
                        <div class="card card-post h-100">
                            @if($post->cover)
                                <img src="{{ asset('storage/' . $post->cover) }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex gap-2 mb-3">
                                    @foreach($post->tags as $tag)
                                        <span class="badge bg-light text-primary">#{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                                <h4 class="card-title h5">{{ $post->title }}</h4>
                                <p class="card-text text-muted small mt-3">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                                <a href="{{ route('post.show', $post->slug) }}" class="text-decoration-none fw-bold mt-auto" style="color: var(--accent-color);">Lire la suite <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection