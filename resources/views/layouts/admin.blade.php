<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jangal CMS - Administration</title>
    
    <!-- Police Inter (Moderne) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tabler CSS (Remplace Bootstrap classique) -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet">
    
    <!-- Icônes Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css">

        <style>
        body { font-family: 'Inter', sans-serif; }
        .navbar-vertical .navbar-brand { color: white; font-weight: bold; font-size: 1.25rem; }
        
        /* --- CORRECTION DE LA COULEUR DES LIENS --- */
        .navbar-vertical .navbar-nav .nav-link {
            color: #cbd5e1 !important; /* Gris clair */
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin-bottom: 2px;
        }

        /* Effet au survol de la souris */
        .navbar-vertical .navbar-nav .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Lien actif (la page en cours) */
        .navbar-vertical .navbar-nav .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: 600;
        }

        /* Icônes */
        .navbar-vertical .navbar-nav .nav-link i { 
            margin-right: 10px; 
            font-size: 1.1rem; 
            color: inherit;
        }
        
        /* Cartes */
        .card { 
            border: none; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); 
        }
        
        /* Badges dans les cartes du dashboard */
        .avatar { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            width: 48px; 
            height: 48px; 
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- SIDEBAR -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark" style="background-color: #1a202c;">
            <div class="container-fluid">
                <h1 class="navbar-brand navbar-brand-autodark mb-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill text-warning"></i> Jangal CMS
                    </a>
                </h1>
                
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3 w-100">
                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-grid-1x2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">
                                <i class="bi bi-file-earmark-text"></i> Articles
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                                <i class="bi bi-layout-text-window"></i> Pages
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">
                                <i class="bi bi-images"></i> Médiathèque
                            </a>
                        </li>
                        
                        <hr class="text-secondary my-3">
                        
                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.themes.*') ? 'active' : '' }}" href="{{ route('admin.themes.index') }}">
                                <i class="bi bi-palette"></i> Thèmes
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.plugins.*') ? 'active' : '' }}" href="{{ route('admin.plugins.index') }}">
                                <i class="bi bi-puzzle"></i> Plugins
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.widgets.*') ? 'active' : '' }}" href="{{ route('admin.widgets.index') }}">
                                <i class="bi bi-layout-sidebar"></i> Widgets
                            </a>
                        </li>

                        <hr class="text-secondary my-3">
                        <!-- HOOK POUR LES PLUGINS (Journal, Charte graphique...) -->
                        {!! do_action('admin_sidebar_links') !!}

                        <hr class="text-secondary my-3">

                        <li class="nav-item mb-1">
                            <a class="nav-link sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people"></i> Utilisateurs
                            </a>
                        </li>
                        <li class="nav-item mb-1 mt-2">
                            <a class="nav-link sidebar-link text-info" href="/" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i> Voir le site
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="page-wrapper">
            <!-- TOP HEADER -->
            <header class="navbar navbar-expand-md d-print-none bg-white border-bottom">
                <div class="container-xl d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="page-title text-dark fw-bold mb-0">@yield('title', 'Administration')</h2>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">Connecté en tant que <strong>{{ Auth::user()->name }}</strong></span>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <div class="page-body">
                <div class="container-xl py-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Tabler JS (Inclut Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6"></script>
    
    <!-- Ta modale média existante -->
    <div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
        <!-- ... (Garde le code de ta modale média ici) ... -->
    </div>
</body>
</html>