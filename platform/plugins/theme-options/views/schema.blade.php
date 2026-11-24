@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>⚙️ Structure du formulaire</h1>
        <a href="{{ route('admin.theme-options.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.theme-options.schema.update') }}" method="POST">
                @csrf
                
                <div id="fields-container">
                    @foreach($schema as $index => $field)
                        <div class="row mb-2 field-row bg-light p-2 rounded border">
                            <div class="col-md-3">
                                <input type="text" name="fields[{{ $index }}][name]" class="form-control" placeholder="Nom de la variable (ex: main_color)" value="{{ $field['name'] }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="fields[{{ $index }}][label]" class="form-control" placeholder="Label affiché" value="{{ $field['label'] }}" required>
                            </div>
                            <div class="col-md-2">
                                <select name="fields[{{ $index }}][type]" class="form-select field-type">
                                    <option value="color" {{ $field['type']=='color'?'selected':'' }}>Couleur</option>
                                    <option value="text" {{ $field['type']=='text'?'selected':'' }}>Texte</option>
                                    <option value="textarea" {{ $field['type']=='textarea'?'selected':'' }}>Texte Long</option>
                                    <option value="select" {{ $field['type']=='select'?'selected':'' }}>Liste</option>
                                    <option value="file" {{ $field['type']=='file'?'selected':'' }}>Fichier</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="fields[{{ $index }}][default]" class="form-control" placeholder="Défaut" value="{{ $field['default'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger w-100 remove-field">Supprimer</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-field" class="btn btn-outline-primary btn-sm mt-3 mb-4">+ Ajouter un champ</button>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Enregistrer la structure</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let container = document.getElementById('fields-container');
            let addBtn = document.getElementById('add-field');
            let count = {{ count($schema) }};

            addBtn.addEventListener('click', function() {
                let row = document.createElement('div');
                row.className = 'row mb-2 field-row bg-light p-2 rounded border';
                row.innerHTML = `
                    <div class="col-md-3"><input type="text" name="fields[${count}][name]" class="form-control" placeholder="Nom de la variable" required></div>
                    <div class="col-md-3"><input type="text" name="fields[${count}][label]" class="form-control" placeholder="Label affiché" required></div>
                    <div class="col-md-2">
                        <select name="fields[${count}][type]" class="form-select">
                            <option value="color">Couleur</option>
                            <option value="text">Texte</option>
                            <option value="textarea">Texte Long</option>
                            <option value="select">Liste</option>
                            <option value="file">Fichier</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="fields[${count}][default]" class="form-control" placeholder="Défaut"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger w-100 remove-field">Supprimer</button></div>
                `;
                container.appendChild(row);
                count++;
            });

            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-field')) {
                    e.target.closest('.field-row').remove();
                }
            });
        });
    </script>
@endsection