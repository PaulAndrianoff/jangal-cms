@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Fichiers du thème : <span class="text-primary">{{ $slug }}</span></h1>
        <a href="{{ route('admin.themes.index') }}" class="btn btn-secondary">Retour aux thèmes</a>
    </div>

    <div class="card shadow-sm col-md-8">
        <div class="card-header bg-white fw-bold">Explorateur de fichiers</div>
        <div class="list-group list-group-flush">
            @foreach($fileList as $file)
                <a href="{{ route('admin.themes.editFile', ['slug' => $slug, 'file' => $file]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span>
                        <!-- Icône selon l'extension -->
                        @if(Str::endsWith($file, '.blade.php')) 📄
                        @elseif(Str::endsWith($file, '.css')) 🎨
                        @elseif(Str::endsWith($file, '.js')) ⚙️
                        @elseif(Str::endsWith($file, '.json')) 📦
                        @else 📁 @endif
                        
                        <code class="ms-2">{{ $file }}</code>
                    </span>
                    <span class="badge bg-secondary">Éditer</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection