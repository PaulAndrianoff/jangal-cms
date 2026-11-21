@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Modifier l'article : {{ $post->title }}</h1>
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title', $post->title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Contenu</label>
                <textarea id="content-editor" name="content" class="form-control" rows="10">{{ old('content', $post->content ?? '') }}</textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nouvelle Image de couverture (laisser vide pour garder l'actuelle)</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nouveau Média (laisser vide pour garder l'actuel)</label>
                    <input type="file" name="media_file" class="form-control" accept="video/mp4,application/pdf">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Tags (séparés par des virgules)</label>
                <input type="text" name="tags" class="form-control" value="{{ old('tags', $post->tags->pluck('name')->implode(', ')) }}">
            </div>
            <div class="mb-4">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Publié</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',

            // NOUVEAU : Configuration pour l'upload direct dans la médiathèque
            images_upload_url: '{{ route("admin.media.upload") }}',
            images_upload_credentials: true,
            automatic_uploads: true,
            images_upload_handler: function(blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = true;
                    xhr.open('POST', '{{ route("admin.media.upload") }}');

                    // Ajout du jeton CSRF de Laravel
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.upload.onprogress = function(e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var json = JSON.parse(xhr.responseText);
                            resolve(json.location);
                        } else {
                            reject('HTTP Error: ' + xhr.status);
                        }
                    };

                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                });
            }
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