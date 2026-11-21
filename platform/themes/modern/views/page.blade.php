@extends('theme::master')

@section('title', $page->title)

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h1 class="mb-4 fw-bold">{{ $page->title }}</h1>
                <div class="content-html lh-lg fs-5">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection