@extends('install.layout')

@section('content')
    <h4 class="mb-4 fw-bold">Créer votre compte Administrateur</h4>
    <form action="{{ route('install.admin.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold text-muted small">Nom complet</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Ex: Jean Dupont">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold text-muted small">Adresse Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="admin@exemple.com">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-muted small">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-muted small">Confirmation</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
        
        <div class="form-check form-switch mb-4 mt-2 p-3 bg-light rounded-3 border">
            <input class="form-check-input ms-0 me-2" type="checkbox" name="install_demo" id="install_demo" checked>
            <label class="form-check-label fw-bold" for="install_demo">Installer les données de démonstration</label>
            <div class="text-muted small mt-1">Ajoute des articles, pages et active le thème par défaut pour vous aider à démarrer.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100">🚀 Lancer l'installation</button>
    </form>
@endsection