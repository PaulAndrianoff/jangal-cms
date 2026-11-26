@extends('theme::master')

@section('title', $post->title)

@section('content')
    <!-- En-tête de l'article -->
    <header class="py-5" style="background-color: var(--light-bg); margin-top: 80px;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <!-- Tags -->
                    <div class="mb-3">
                        @foreach($post->tags as $tag)
                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill me-1 text-decoration-none" style="background-color: var(--accent-color) !important;">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-4" style="color: var(--primary-color);">{{ $post->title }}</h1>
                    
                    <div class="d-flex justify-content-center align-items-center gap-3 text-muted">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            <span class="fw-bold text-dark">{{ $post->author->name }}</span>
                        </div>
                        <span>•</span>
                        <span>{{ $post->created_at->format('d M, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <!-- Contenu Principal -->
            <div class="col-lg-8">
                @if($post->cover)
                    <img src="{{ asset('storage/' . $post->cover) }}" class="img-fluid rounded-4 shadow-sm mb-5 w-100" style="max-height: 500px; object-fit: cover;" alt="{{ $post->title }}">
                @endif

                <div class="content-html lh-lg fs-5" style="color: #475569;">
                    {!! $post->content !!}
                </div>

                <!-- Média attaché (Vidéo/PDF) -->
                @if($post->media_file)
                    <div class="mt-5 p-4 rounded-4" style="background-color: var(--light-bg);">
                        <h5 class="fw-bold mb-3"><i class="bi bi-paperclip"></i> Ressource attachée</h5>
                        @if(Str::endsWith($post->media_file, '.mp4'))
                            <video controls class="w-100 rounded-3">
                                <source src="{{ asset('storage/' . $post->media_file) }}" type="video/mp4">
                            </video>
                        @else
                            <a href="{{ asset('storage/' . $post->media_file) }}" target="_blank" class="btn btn-accent">
                                <i class="bi bi-file-earmark-pdf"></i> Télécharger le document PDF
                            </a>
                        @endif
                    </div>
                @endif
                
                <!-- CTA de fin d'article -->
                <div class="mt-5 p-5 text-center rounded-4" style="background: linear-gradient(135deg, var(--primary-color), #1e293b); color: white;">
                    <h3 class="fw-bold mb-3">Envie de discuter de ce sujet ?</h3>
                    <p class="mb-4 opacity-75">Notre équipe est à votre disposition pour répondre à toutes vos questions.</p>
                    <a href="/contact" class="btn btn-light rounded-pill px-4 fw-bold">Nous contacter</a>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <hr class="my-5">
                <h3 class="fw-bold mb-4">À lire également</h3>
                <div class="row g-4">
                    @foreach($recentPosts as $recent)
                        <div class="col-md-4">
                            <a href="{{ route('post.show', $recent->slug) }}" class="text-decoration-none">
                                <div class="card card-post h-100">
                                    @if($recent->cover)
                                        <img src="{{ asset('storage/' . $recent->cover) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    @endif
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark">{{ Str::limit($recent->title, 40) }}</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection