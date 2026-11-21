@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Articles</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Rédiger un article</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->author->name }}</td>
                            <td>
                                @if($post->status === 'published')
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-warning text-dark">Brouillon</span>
                                @endif
                            </td>
                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet article ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">Aucun article pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $posts->links() }}</div>
@endsection