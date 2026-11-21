<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jangal CMS - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #343a40;
            color: white;
        }

        .sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar col-md-2 d-flex flex-column">
            <h4 class="text-center mb-4">Jangal CMS</h4>
            <a href="{{ route('admin.dashboard') }}" class="active">Tableau de bord</a>
            <a href="{{ route('admin.posts.index') }}">Articles</a>
            <a href="{{ route('admin.pages.index') }}">Pages</a>
            <a href="{{ route('admin.media.index') }}">Médiathèque</a>
            <a href="{{ route('admin.users.index') }}">Gestion des utilisateurs</a>
            <a href="/" target="_blank">Voir le site ↗</a>

            <div class="mt-auto pb-4 px-3">
                <hr class="text-secondary">
                <span class="d-block text-light mb-2">👤 {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger w-100">Déconnexion</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Modale de la Médiathèque -->
    <div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sélectionner un média</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="media-grid">
                        <!-- Les images seront injectées ici par JavaScript -->
                        <div class="col-12 text-center text-muted py-4">Chargement des médias...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6"></script>
    <!-- Modale de la Médiathèque (déjà existante) -->
    <!-- ... (ton code de la modale) ... -->

    <script>
        // Variable globale pour stocker l'action à faire au clic
        window.mediaModalCallback = null;

        // Fonction globale pour ouvrir la modale avec une action spécifique
        window.openMediaModal = function(callback) {
            window.mediaModalCallback = callback;
            var modal = new bootstrap.Modal(document.getElementById('mediaModal'));
            modal.show();

            let grid = document.getElementById('media-grid');
            if (grid.dataset.loaded !== 'true') {
                fetch('{{ route("admin.media.list") }}')
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = '<div class="col-12 text-center text-muted py-4">Aucun média trouvé.</div>';
                        } else {
                            data.forEach(item => {
                                let url = '/storage/' + item.path;
                                html += `
                                <div class="col-3 mb-3">
                                    <img src="${url}" 
                                         style="width:100%; height:120px; object-fit:cover; cursor:pointer; border-radius:5px; border: 2px solid transparent; transition: 0.2s;" 
                                         onclick="if(window.mediaModalCallback) window.mediaModalCallback('${url}');"
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
        };
    </script>
</body>

</html>