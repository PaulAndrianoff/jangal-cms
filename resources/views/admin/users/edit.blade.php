@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Modifier l'utilisateur : {{ $user->name }}</h1>
    
    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" {{ $user->is_admin ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <label class="form-check-label" for="is_admin">Droits d'administration</label>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection