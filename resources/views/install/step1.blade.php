@extends('install.layout')

@section('content')
<h3 class="mb-3">Vérification du serveur</h3>
<ul class="list-group mb-4">
    @foreach($requirements as $key => $passed)
    <li class="list-group-item d-flex justify-content-between align-items-center">
        {{ ucfirst($key) }}
        @if($passed)
        <span class="badge bg-success">OK</span>
        @else
        <span class="badge bg-danger">Échec</span>
        @endif
    </li>
    @endforeach
</ul>

@if($allPassed)
<a href="{{ route('install.database') }}" class="btn btn-primary w-100">Continuer</a>
@else
<div class="alert alert-warning">Veuillez corriger les erreurs ci-dessus avant de continuer.</div>
@endif
@endsection