@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier le plugin : {{ $slug }}</h1>
        <a href="{{ route('admin.plugins.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('admin.plugins.update', $slug) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Nom du plugin</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $plugin['name']) }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $plugin['description'] ?? '') }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Version</label>
                        <input type="text" name="version" class="form-control" required value="{{ old('version', $plugin['version']) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Auteur</label>
                        <input type="text" name="author" class="form-control" value="{{ old('author', $plugin['author'] ?? '') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
@endsection