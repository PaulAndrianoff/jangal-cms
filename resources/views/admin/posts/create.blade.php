@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Rédiger un article</h1>
<div class="card shadow-sm">
    <div class="card-body">
        <!-- N'OUBLIE PAS enctype="multipart/form-data" POUR LES FICHIERS -->
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Titre de l'article</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Contenu</label>
                <textarea id="content-editor" name="content" class="form-control" rows="10">{{ old('content', $post->content ?? '') }}</textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Image de couverture (JPG/PNG max 2Mo)</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Média (Vidéo MP4 ou PDF max 10Mo)</label>
                    <input type="file" name="media_file" class="form-control" accept="video/mp4,application/pdf">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Tags (séparés par des virgules)</label>
                <input type="text" name="tags" class="form-control" placeholder="ex: Laravel, PHP, Web" value="{{ old('tags') }}">
            </div>
            <div class="mb-4">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publié</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer l'article</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#content-editor',
            height: 400,
            menubar: false,
            base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6',
            suffix: '.min',
            // Le plugin 'paste' a été retiré de cette liste
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    });

    // Validation avant soumission
    document.querySelector('form').addEventListener('submit', function(e) {
        var content = tinymce.get('content-editor').getContent();
        if (!content || content.trim() === '') {
            e.preventDefault(); // Bloque l'envoi
            alert('Le contenu de l\'article ne peut pas être vide.');
        }
    });
</script>
@endsection