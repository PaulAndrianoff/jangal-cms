@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Modifier la page : {{ $page->title }}</h1>
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title', $page->title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Contenu</label>
                <textarea id="content-editor" name="content" class="form-control" rows="10">{{ old('content', $page->content) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ $page->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ $page->status == 'published' ? 'selected' : '' }}>Publiée</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<!-- TinyMCE -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@6"></script>
<script>
    // Fonction globale appelée quand on clique sur une image dans la modale
    window.insertMediaInEditor = function(url) {
        tinymce.activeEditor.insertContent('<img src="' + url + '" style="max-width:100%;" />');
        // On ferme la modale avec Bootstrap
        var modal = bootstrap.Modal.getInstance(document.getElementById('mediaModal'));
        modal.hide();
    };

    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#content-editor',
            height: 400,
            menubar: false,
            base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6',
            suffix: '.min',
            // Ajout du bouton 'mediabtn' dans la toolbar
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | mediabtn | code',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            
            // Configuration de l'upload direct (si on glisse-dépose)
            images_upload_url: '{{ route("admin.media.upload") }}',
            automatic_uploads: true,
            images_upload_handler: function (blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route("admin.media.upload") }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var json = JSON.parse(xhr.responseText);
                            resolve(json.location);
                        } else {
                            reject('HTTP Error: ' + xhr.status);
                        }
                    };
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                });
            },

            // Création du bouton personnalisé
            setup: function (editor) {
                editor.ui.registry.addButton('mediabtn', {
                    icon: 'gallery', // Icône de galerie
                    tooltip: 'Insérer depuis la médiathèque',
                    onAction: function () {
                        // 1. Ouvrir la modale Bootstrap
                        var modal = new bootstrap.Modal(document.getElementById('mediaModal'));
                        modal.show();

                        // 2. Charger les images en AJAX si ce n'est pas déjà fait
                        let grid = document.getElementById('media-grid');
                        if (grid.dataset.loaded !== 'true') {
                            fetch('{{ route("admin.media.list") }}')
                                .then(response => response.json())
                                .then(data => {
                                    let html = '';
                                    if(data.length === 0) {
                                        html = '<div class="col-12 text-center text-muted py-4">Aucun média trouvé. <a href="{{ route("admin.media.index") }}" target="_blank">Uploadez-en ici</a>.</div>';
                                    } else {
                                        data.forEach(item => {
                                            let url = '/storage/' + item.path;
                                            html += `
                                                <div class="col-3 mb-3">
                                                    <img src="${url}" 
                                                         style="width:100%; height:120px; object-fit:cover; cursor:pointer; border-radius:5px; border: 2px solid transparent; transition: 0.2s;" 
                                                         onclick="insertMediaInEditor('${url}')"
                                                         onmouseover="this.style.borderColor='#0d6efd'"
                                                         onmouseout="this.style.borderColor='transparent'"
                                                         title="${item.name}">
                                                </div>
                                            `;
                                        });
                                    }
                                    grid.innerHTML = html;
                                    grid.dataset.loaded = 'true';
                                });
                        }
                    }
                });
            }
        });
    });
</script>
@endsection