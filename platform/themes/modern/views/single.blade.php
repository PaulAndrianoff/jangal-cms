@extends('theme::master')

@section('title', $post->title)

@section('content')
    <div class="container">
        <div class="row">
            <!-- Article Principal -->
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 30) }}</li>
                    </ol>
                </nav>

                <h1 class="mb-3 fw-bold">{{ $post->title }}</h1>
                <p class="text-muted border-bottom pb-3">
                    Rédigé par <strong>{{ $post->author->name }}</strong> le {{ $post->created_at->format('d/m/Y') }}
                </p>

                @if($post->cover)
                    <img src="{{ asset('storage/' . $post->cover) }}" class="img-fluid rounded mb-4 w-100" style="max-height: 400px; object-fit: cover;" alt="{{ $post->title }}">
                @endif

                <!-- Contenu HTML -->
                <div class="content-html lh-lg fs-5">
                    {!! $post->content !!}
                </div>

                <!-- Média attaché -->
                @if($post->media_file)
                    <div class="mt-5 p-3 bg-white border rounded">
                        <h5 class="border-bottom pb-2">Média attaché :</h5>
                        @if(Str::endsWith($post->media_file, '.mp4'))
                            <video controls class="w-100 mt-2 rounded">
                                <source src="{{ asset('storage/' . $post->media_file) }}" type="video/mp4">
                            </video>
                        @else
                            <a href="{{ asset('storage/' . $post->media_file) }}" target="_blank" class="btn btn-primary mt-2">
                                📄 Télécharger le document PDF
                            </a>
                        @endif
                    </div>
                @endif
                
                <div class="mt-4 mb-5">
                    <strong>Tags :</strong>
                    @foreach($post->tags as $tag)
                        <span class="badge bg-secondary text-decoration-none">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Articles récents</div>
                    <ul class="list-group list-group-flush">
                        @foreach($recentPosts as $recent)
                            <li class="list-group-item">
                                <a href="{{ route('post.show', $recent->slug) }}" class="text-decoration-none text-dark fw-semibold">
                                    {{ $recent->title }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $recent->created_at->format('d/m/Y') }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection