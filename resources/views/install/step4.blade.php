@extends('install.layout')

@section('content')
    <div class="text-center py-4">
        <h1 class="display-4 text-success mb-3">🎉</h1>
        <h3>Installation réussie !</h3>
        <p class="text-muted mb-4">Votre CMS est maintenant prêt à être utilisé.</p>
        
        <div class="d-grid gap-2 col-md-6 mx-auto">
            <a href="/admin" class="btn btn-primary">Accéder au panneau d'administration</a>
            <a href="/" class="btn btn-outline-secondary">Voir le site</a>
        </div>
    </div>
@endsection