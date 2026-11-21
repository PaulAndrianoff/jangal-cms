@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Tableau de bord</h1>

    <div class="row">
        <!-- Gestion des Plugins -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    🔌 Gestion des Plugins
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plugins as $plugin)
                                <tr>
                                    <td>
                                        <strong>{{ $plugin['name'] ?? $plugin['slug'] }}</strong><br>
                                        <small class="text-muted">v{{ $plugin['version'] ?? '1.0.0' }}</small>
                                    </td>
                                    <td>
                                        @if(in_array($plugin['slug'], $activePlugins))
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.plugins.toggle', $plugin['slug']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ in_array($plugin['slug'], $activePlugins) ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                {{ in_array($plugin['slug'], $activePlugins) ? 'Désactiver' : 'Activer' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-3">Aucun plugin trouvé.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gestion des Thèmes -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    🎨 Gestion des Thèmes
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($themes as $theme)
                                <tr>
                                    <td>
                                        <strong>{{ $theme['name'] ?? $theme['slug'] }}</strong><br>
                                        <small class="text-muted">v{{ $theme['version'] ?? '1.0.0' }}</small>
                                    </td>
                                    <td>
                                        @if($activeTheme && $activeTheme->name === $theme['slug'])
                                            <span class="badge bg-primary">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(!$activeTheme || $activeTheme->name !== $theme['slug'])
                                            <form action="{{ route('admin.themes.activate', $theme['slug']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Activer</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-primary" disabled>Déjà Actif</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-3">Aucun thème trouvé.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection