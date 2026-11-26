@extends('theme::master')

@section('title', $page->title)

@section('content')
    <!-- En-tête de la page -->
    <header class="py-5" style="background-color: var(--primary-color); margin-top: 80px;">
        <div class="container py-5 text-center">
            <h1 class="display-4 fw-bold text-white mb-0">{{ $page->title }}</h1>
        </div>
    </header>

    <!-- Contenu de la page -->
    <div class="container py-5 my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <div class="content-html lh-lg fs-5" style="color: #475569;">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection