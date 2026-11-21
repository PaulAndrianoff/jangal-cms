@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Médiathèque</h1>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                @csrf
                <input type="file" name="file" class="form-control" required>
                <button type="submit" class="btn btn-primary text-nowrap">Uploader</button>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($media as $item)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    @if(Str::startsWith($item->mime_type, 'image/'))
                        <img src="{{ asset('storage/' . $item->path) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 150px;">
                            {{ strtoupper(explode('/', $item->mime_type)[1]) }}
                        </div>
                    @endif
                    
                    <div class="card-body p-2">
                        <small class="d-block text-truncate text-muted" title="{{ $item->name }}">{{ $item->name }}</small>
                        <small class="d-block text-muted">{{ round($item->size / 1024, 2) }} Ko</small>
                        
                        <div class="mt-2 d-flex gap-1">
                            <!-- Bouton pour copier l'URL -->
                            <button onclick="navigator.clipboard.writeText('{{ asset('storage/' . $item->path) }}'); alert('URL copiée !');" class="btn btn-sm btn-outline-secondary w-100">Copier l'URL</button>
                            
                            <form action="{{ route('admin.media.destroy', $item) }}" method="POST" onsubmit="return confirm('Supprimer ce média ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">X</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Aucun média pour le moment.</div>
        @endforelse
    </div>
    <div class="mt-3">{{ $media->links() }}</div>
@endsection