@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Édition : <code>{{ $file }}</code></h1>
        <a href="{{ route('admin.themes.files', $slug) }}" class="btn btn-secondary">Retour aux fichiers</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.themes.updateFile', $slug) }}" method="POST">
        @csrf
        <input type="hidden" name="file" value="{{ $file }}">
        
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <!-- On charge CodeMirror pour la coloration syntaxique -->
                <textarea name="content" id="code-editor" class="form-control" rows="20" style="font-family: monospace; display: none;">{{ $content }}</textarea>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            </div>
        </div>
    </form>

    <!-- CodeMirror CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/php/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/clike/clike.min.js"></script>

    <script>
        var editor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
            lineNumbers: true,
            mode: "application/x-httpd-php", // Mode par défaut (gère aussi HTML)
            theme: "default",
            indentUnit: 4,
            lineWrapping: true,
            height: "600px"
        });
    </script>
    <style>
        .CodeMirror { height: 600px; border: 1px solid #ddd; }
    </style>
@endsection