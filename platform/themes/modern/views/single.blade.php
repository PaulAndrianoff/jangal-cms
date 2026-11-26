@extends('theme::master')

@section('title', $post->title)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- En-tête de l'article -->
                <div class="text-center mb-5">
                    <div class="mb-3">
                        @foreach($post->tags as $tag)
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill me-1">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <h1 class="hero-title display-4 mb-4">{{ $post->title }}</h1>
                    <div class="d-flex justify-content-center align-items-center gap-3 text-muted">
                        <span><i class="bi bi-person-circle me-1"></i> {{ $post->author->name }}</span>
                        <span>•</span>
                        <span>{{ $post->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Image de couverture -->
                @if($post->cover)
                    <img src="{{ asset('storage/' . $post->cover) }}" class="img-fluid w-100 rounded-4 shadow-sm mb-5" style="max-height: 500px; object-fit: cover;">
                @endif

                <!-- Contenu de l'article -->
                <div class="content-html">
                    {!! $post->content !!}
                </div>

                <!-- Média attaché -->
                @if($post->media_file)
                    <div class="mt-5 p-4 rounded-4 bg-white border">
                        <h5 class="fw-bold mb-3"><i class="bi bi-paperclip"></i> Fichier joint</h5>
                        @if(Str::endsWith($post->media_file, '.mp4'))
                            <video controls class="w-100 rounded-3">
                                <source src="{{ asset('storage/' . $post->media_file) }}" type="video/mp4">
                            </video>
                        @else
                            <a href="{{ asset('storage/' . $post->media_file) }}" target="_blank" class="btn btn-primary">
                                📄 Télécharger le document
                            </a>
                        @endif
                    </div>
                @endif
                
                <!-- Auteur -->
                <div class="mt-5 p-4 rounded-4 bg-white border d-flex align-items-center gap-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fs-3" style="width: 70px; height: 70px;">
                        {{ substr($post->author->name, 0, 1) }}
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">{{ $post->author->name }}</h5>
                        <p class="text-muted mb-0 small">Auteur sur Jangal CMS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection