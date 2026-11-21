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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6"></script>
</body>

</html>