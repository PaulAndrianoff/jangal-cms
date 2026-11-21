@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Pages</h1>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Créer une page</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Slug (URL)</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->title }}</td>
                            <td><code>/page/{{ $page->slug }}</code></td>
                            <td>
                                @if($page->status === 'published')
                                    <span class="badge bg-success">Publiée</span>
                                @else
                                    <span class="badge bg-warning text-dark">Brouillon</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette page ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4">Aucune page créée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection