@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <!-- Cartes de Statistiques -->
    <div class="row row-cards mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar rounded"><i class="bi bi-file-earmark-text fs-2"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-3 fw-bold">{{ $stats['posts'] }}</div>
                            <div class="text-muted">Articles publiés</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-success text-white avatar rounded"><i class="bi bi-layout-text-window fs-2"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-3 fw-bold">{{ $stats['pages'] }}</div>
                            <div class="text-muted">Pages créées</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-warning text-white avatar rounded"><i class="bi bi-images fs-2"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-3 fw-bold">{{ $stats['media'] }}</div>
                            <div class="text-muted">Médias</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-info text-white avatar rounded"><i class="bi bi-people fs-2"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-3 fw-bold">{{ $stats['users'] }}</div>
                            <div class="text-muted">Utilisateurs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gestion des Plugins -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="bi bi-puzzle me-2"></i> Plugins</h3>
                    <a href="{{ route('admin.plugins.index') }}" class="btn btn-sm btn-outline-primary">Gérer</a>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plugins as $plugin)
                                <tr>
                                    <td>
                                        <strong>{{ $plugin['name'] ?? $plugin['slug'] }}</strong><br>
                                        <span class="text-muted small">v{{ $plugin['version'] ?? '1.0.0' }}</span>
                                    </td>
                                    <td>
                                        @if(in_array($plugin['slug'], $activePlugins))
                                            <span class="badge bg-success text-white">Actif</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Inactif</span>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gestion des Thèmes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="bi bi-palette me-2"></i> Thèmes</h3>
                    <a href="{{ route('admin.themes.index') }}" class="btn btn-sm btn-outline-primary">Gérer</a>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($themes as $theme)
                                <tr>
                                    <td>
                                        <strong>{{ $theme['name'] ?? $theme['slug'] }}</strong><br>
                                        <span class="text-muted small">v{{ $theme['version'] ?? '1.0.0' }}</span>
                                    </td>
                                    <td>
                                        @if($activeTheme && $activeTheme->name === $theme['slug'])
                                            <span class="badge bg-primary text-white">Actif</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(!$activeTheme || $activeTheme->name !== $theme['slug'])
                                            <form action="{{ route('admin.themes.activate', $theme['slug']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Activer</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-primary" disabled>Actif</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection