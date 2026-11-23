@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Paramètres du Menu</h1>
        <a href="{{ route('admin.widgets.index') }}" class="btn btn-secondary">Retour aux widgets</a>
    </div>

    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('admin.widgets.update', $widget) }}" method="POST">
                @csrf @method('PUT')
                
                <p class="text-muted small mb-4">Ajoutez des liens personnalisés à votre menu. Laissez vide pour afficher automatiquement toutes les pages du site.</p>

                <div id="menu-items-container">
                    @php
                        $items = $widget->settings['items'] ?? [];
                    @endphp

                    @foreach($items as $index => $item)
                        <div class="row mb-2 menu-item-row">
                            <div class="col-5">
                                <input type="text" name="settings[items][{{ $index }}][label]" class="form-control" placeholder="Nom du lien" value="{{ $item['label'] ?? '' }}" required>
                            </div>
                            <div class="col-5">
                                <input type="text" name="settings[items][{{ $index }}][url]" class="form-control" placeholder="URL (ex: /contact)" value="{{ $item['url'] ?? '' }}" required>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger w-100 remove-item">X</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-item" class="btn btn-outline-primary btn-sm mt-2 mb-4">+ Ajouter un lien</button>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer les paramètres</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let container = document.getElementById('menu-items-container');
            let addBtn = document.getElementById('add-item');
            let itemCount = {{ count($items) }};

            addBtn.addEventListener('click', function() {
                let row = document.createElement('div');
                row.className = 'row mb-2 menu-item-row';
                row.innerHTML = `
                    <div class="col-5">
                        <input type="text" name="settings[items][${itemCount}][label]" class="form-control" placeholder="Nom du lien" required>
                    </div>
                    <div class="col-5">
                        <input type="text" name="settings[items][${itemCount}][url]" class="form-control" placeholder="URL (ex: /contact)" required>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger w-100 remove-item">X</button>
                    </div>
                `;
                container.appendChild(row);
                itemCount++;
            });

            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-item')) {
                    e.target.closest('.menu-item-row').remove();
                }
            });
        });
    </script>
@endsection