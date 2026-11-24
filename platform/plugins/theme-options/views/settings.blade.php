@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🎨 Charte Graphique</h1>
        <a href="{{ route('admin.theme-options.schema') }}" class="btn btn-outline-dark">⚙️ Gérer les champs du formulaire</a>
    </div>

    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="{{ route('admin.theme-options.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @foreach($schema as $field)
                    @php $value = $config[$field['name']] ?? $field['default']; @endphp
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ $field['label'] }}</label>

                        {{-- CHAMP COULEUR --}}
                        @if($field['type'] === 'color')
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" name="{{ $field['name'] }}" class="form-control form-control-color" value="{{ $value }}">
                                <span class="text-muted"><code>var(--{{ str_replace('_', '-', $field['name']) }})</code></span>
                            </div>

                        {{-- CHAMP SELECT --}}
                        @elseif($field['type'] === 'select')
                            <select name="{{ $field['name'] }}" class="form-select">
                                @foreach($field['options'] ?? [] as $option)
                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>

                        {{-- CHAMP FICHIER --}}
                        @elseif($field['type'] === 'file')
                            @if($value)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $value) }}" style="max-height: 50px; background: #eee; padding: 5px; border-radius: 5px;">
                                </div>
                            @endif
                            <input type="file" name="{{ $field['name'] }}" class="form-control" accept="image/*">
                        
                        {{-- CHAMP TEXTAREA --}}
                        @elseif($field['type'] === 'textarea')
                            <textarea name="{{ $field['name'] }}" class="form-control" rows="4">{{ $value }}</textarea>

                        {{-- CHAMP TEXTE --}}
                        @else
                            <input type="text" name="{{ $field['name'] }}" class="form-control" value="{{ $value }}">
                        @endif
                    </div>
                @endforeach

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">💾 Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endsection