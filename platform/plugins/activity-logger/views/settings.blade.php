@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Paramètres du Journal</h1>
        <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">Retour au journal</a>
    </div>

    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('admin.logs.settings.update') }}" method="POST">
                @csrf
                <p class="text-muted mb-4">Choisissez les actions que vous souhaitez enregistrer dans le journal d'activité.</p>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="log_auth" id="log_auth" {{ $config['log_auth'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="log_auth">Connexions et déconnexions (Auth)</label>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="log_posts" id="log_posts" {{ $config['log_posts'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="log_posts">Création / Modification / Suppression d'Articles</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="log_pages" id="log_pages" {{ $config['log_pages'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="log_pages">Création / Modification / Suppression de Pages</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="log_users" id="log_users" {{ $config['log_users'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="log_users">Gestion des Utilisateurs</label>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="log_media" id="log_media" {{ $config['log_media'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="log_media">Upload / Suppression de Médias</label>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>
            </form>
        </div>
    </div>
@endsection