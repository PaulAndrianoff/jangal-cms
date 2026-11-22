@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Gestion des Widgets</h1>

    <div class="row">
        <!-- Formulaire d'ajout -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Ajouter un widget</div>
                <div class="card-body">
                    <form action="{{ route('admin.widgets.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Zone d'affichage</label>
                            <select name="area" class="form-select" required>
                                @foreach($areas as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Type de widget</label>
                            <select name="type" class="form-select" required>
                                @foreach($availableWidgets as $key => $widget)
                                    <option value="{{ $key }}">{{ $widget['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ajouter le widget</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des widgets actifs -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Widgets actifs</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Zone</th>
                                <th>Widget</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeWidgets as $widget)
                                <tr>
                                    <td><span class="badge bg-info text-dark">{{ $areas[$widget->area] ?? $widget->area }}</span></td>
                                    <td>{{ $availableWidgets[$widget->type]['name'] ?? $widget->type }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.widgets.destroy', $widget) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Retirer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-4">Aucun widget actif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection