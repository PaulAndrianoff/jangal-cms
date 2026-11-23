@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Plugins</h1>
        
        <form action="{{ route('admin.plugins.upload') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="plugin_zip" class="form-control form-control-sm" accept=".zip" required>
            <button type="submit" class="btn btn-success btn-sm text-nowrap">Installer un plugin (.zip)</button>
        </form>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Version</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plugins as $plugin)
                        @php $isActive = in_array($plugin['slug'], $activePlugins); @endphp
                        <tr>
                            <td>
                                <strong>{{ $plugin['name'] ?? $plugin['slug'] }}</strong><br>
                                <small class="text-muted">{{ $plugin['description'] ?? '' }}</small>
                            </td>
                            <td>v{{ $plugin['version'] ?? '1.0.0' }}</td>
                            <td>
                                @if($isActive)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <!-- Modifier -->
                                <a href="{{ route('admin.plugins.edit', $plugin['slug']) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>

                                <!-- Activer / Désactiver -->
                                <form action="{{ route('admin.plugins.toggle', $plugin['slug']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $isActive ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        {{ $isActive ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>

                                <!-- Supprimer -->
                                <form action="{{ route('admin.plugins.destroy', $plugin['slug']) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce plugin ? Toutes ses données seront perdues.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">X</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4">Aucun plugin installé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection