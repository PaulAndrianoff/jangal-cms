@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Ajouter un utilisateur</h1>
    
    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" {{ old('is_admin') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_admin">Donner les droits d'administration</label>
                </div>
                <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection