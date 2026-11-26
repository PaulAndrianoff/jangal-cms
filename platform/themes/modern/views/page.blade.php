@extends('theme::master')

@section('title', $page->title)

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
                    <h1 class="hero-title fs-1 mb-4 text-center">{{ $page->title }}</h1>
                    <hr class="mx-auto mb-5 text-black-50" style="width: 50px;">
                    
                    <div class="content-html">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection