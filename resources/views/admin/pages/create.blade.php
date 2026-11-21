@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Créer une page</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.pages.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Titre de la page</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contenu</label>
                    <textarea id="content-editor" name="content" class="form-control" rows="10">{{ old('content') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="draft">Brouillon</option>
                        <option value="published">Publiée</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer la page</button>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>

    <!-- TinyMCE -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: '#content-editor',
                height: 400,
                menubar: false,
                base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6',
                suffix: '.min',
                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code'
            });
        });
    </script>
@endsection