@extends('theme::master')

@section('title', $page->title)

@section('content')
    <!-- Contenu de la page -->
    <div class="container my-5 py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Titre de la page -->
                <div class="text-center mb-5">
                    <h1 class="display-3 mb-4" style="line-height: 1.1;">{{ $page->title }}</h1>
                    <hr class="mx-auto" style="width: 50px; border-top: 2px solid #1a1a1a; opacity: 1;">
                </div>

                <!-- Contenu (Texte ou HTML) -->
                <div class="content-html serif fs-5 lh-lg" style="color: #333;">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection