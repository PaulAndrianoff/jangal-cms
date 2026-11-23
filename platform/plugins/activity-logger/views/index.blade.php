@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Journal d'activité</h1>
        <a href="{{ route('admin.logs.settings') }}" class="btn btn-outline-secondary">⚙️ Paramètres du journal</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td><small>{{ $log->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>{{ $log->user ? $log->user->name : 'Système' }}</td>
                            <td><span class="badge bg-secondary">{{ $log->module }}</span></td>
                            <td>
                                @if($log->action === 'created') <span class="text-success">Création</span>
                                @elseif($log->action === 'updated') <span class="text-primary">Modification</span>
                                @elseif($log->action === 'deleted') <span class="text-danger">Suppression</span>
                                @else {{ $log->action }} @endif
                            </td>
                            <td>{{ $log->description }}</td>
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Aucune activité enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $logs->links() }}</div>
@endsection