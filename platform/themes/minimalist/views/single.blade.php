@extends('theme::master')
@section('title', $post->title)

@section('content')
    <div class="container my-5 pt-5" style="max-width: 900px;">
        <div class="text-center mb-5">
            <span class="text-muted text-uppercase" style="letter-spacing: 2px;">{{ $post->created_at->format('d F Y') }}</span>
            <h1 class="display-4 mt-3 mb-4">{{ $post->title }}</h1>
            <p class="text-muted">Par <strong>{{ $post->author->name }}</strong></p>
        </div>

        @if($post->cover)
            <img src="{{ asset('storage/' . $post->cover) }}" class="w-100 mb-5" style="object-fit: cover; max-height: 600px;">
        @endif

        <div class="content-html serif fs-5 lh-lg" style="color: #333;">
            {!! $post->content !!}
        </div>
        
        <div class="mt-5 pt-4 border-top d-flex gap-2">
            @foreach($post->tags as $tag)
                <span class="badge bg-dark rounded-0 px-3 py-2">#{{ $tag->name }}</span>
            @endforeach
        </div>
    </div>
@endsection