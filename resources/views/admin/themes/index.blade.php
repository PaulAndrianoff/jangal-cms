@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion des Thèmes</h1>

    <!-- Formulaire d'upload de thème -->
    <form action="{{ route('admin.themes.upload') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input type="file" name="theme_zip" class="form-control form-control-sm" accept=".zip" required>
        <button type="submit" class="btn btn-success btn-sm text-nowrap">Installer un thème (.zip)</button>
    </form>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    @foreach($themes as $theme)
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100 {{ $activeTheme && $activeTheme->name === $theme['slug'] ? 'border-primary border-2' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">
                            {{ $theme['name'] ?? $theme['slug'] }}
                            @if($activeTheme && $activeTheme->name === $theme['slug'])
                            <span class="badge bg-primary ms-2">Actif</span>
                            @endif
                        </h4>
                        <p class="text-muted small mb-2">Version : {{ $theme['version'] ?? '1.0.0' }} | Par : {{ $theme['author'] ?? 'Inconnu' }}</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">Actions ⋮</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.themes.edit', $theme['slug']) }}">Modifier les infos</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.themes.files', $theme['slug']) }}">Éditer le code</a></li>
                            <li>
                                <form action="{{ route('admin.themes.destroy', $theme['slug']) }}" method="POST" onsubmit="return confirm('Supprimer ce thème ? Cette action est irréversible.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">Supprimer</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <p class="card-text">{{ $theme['description'] ?? 'Aucune description fournie.' }}</p>

                <div class="mt-3">
                    @if(!$activeTheme || $activeTheme->name !== $theme['slug'])
                    <form action="{{ route('admin.themes.activate', $theme['slug']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Activer ce thème</button>
                    </form>
                    @else
                    <button class="btn btn-secondary w-100" disabled>Thème actuellement actif</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection