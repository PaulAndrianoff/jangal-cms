@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Éditeur JSON : <span class="text-primary">{{ $slug }}</span></h1>
        <a href="{{ route('admin.plugins.index') }}" class="btn btn-secondary">Retour aux plugins</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm col-md-10">
        <div class="card-header bg-white">
            <form action="{{ route('admin.plugins.json', $slug) }}" method="GET" class="d-flex align-items-center gap-2">
                <label class="fw-bold mb-0 text-nowrap">Fichier à éditer :</label>
                <select name="file" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    @foreach($jsonFiles as $file)
                        <option value="{{ $file }}" {{ $currentFile === $file ? 'selected' : '' }}>{{ $file }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('admin.plugins.json.update', $slug) }}" method="POST">
                @csrf
                <input type="hidden" name="file_name" value="{{ $currentFile }}">
                
                <textarea name="content" id="json-editor" class="form-control" rows="20" style="font-family: monospace;">{{ $content }}</textarea>
                
                <div class="card-footer bg-white d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer le fichier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- CodeMirror pour la coloration JSON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>

    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("json-editor"), {
            lineNumbers: true,
            mode: {name: "javascript", json: true}, // Mode JSON
            theme: "default",
            indentUnit: 4,
            lineWrapping: true,
            height: "500px"
        });
    </script>
    <style>
        .CodeMirror { height: 500px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
    </style>
@endsection