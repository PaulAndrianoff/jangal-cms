@extends('install.layout')

@section('content')
    <h3 class="mb-3">Configuration de la base de données</h3>
    <form action="{{ route('install.database.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-8">
                <label>Hôte</label>
                <input type="text" name="db_host" class="form-control" value="127.0.0.1" required>
            </div>
            <div class="col-md-4">
                <label>Port</label>
                <input type="text" name="db_port" class="form-control" value="3306" required>
            </div>
        </div>
        <div class="mb-3">
            <label>Nom de la base de données</label>
            <input type="text" name="db_name" class="form-control" required placeholder="ex: jangal_cms">
        </div>
        <div class="mb-3">
            <label>Nom d'utilisateur</label>
            <input type="text" name="db_user" class="form-control" value="root" required>
        </div>
        <div class="mb-4">
            <label>Mot de passe</label>
            <input type="password" name="db_password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Tester la connexion et continuer</button>
    </form>
@endsection