@extends('install.layout')

@section('content')
    <h3 class="mb-3">Créer votre compte Administrateur</h3>
    <form action="{{ route('install.admin.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nom complet</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label>Adresse Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label>Mot de passe (min. 8 caractères)</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-4">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Installer Jangal CMS</button>
    </form>
@endsection