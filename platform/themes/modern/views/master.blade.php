<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon super CMS')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AJOUTE CE BLOC STYLE -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background-color: #550000;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .btn-outline-light {
            color: white;
        }

        .hero {
            background: #2d3748;
            color: white;
            padding: 4rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .post-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .post-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">⚡ Jangal CMS</a>

            <!-- AJOUTE CE BLOC POUR LE MENU DYNAMIQUE -->
            <div class="collapse navbar-collapse ms-4">
                <ul class="navbar-nav me-auto">
                    @php
                    $navPages = \App\Models\Page::where('status', 'published')->get();
                    @endphp
                    @foreach($navPages as $navPage)
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('page.show', $navPage->slug) }}">{{ $navPage->title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- FIN DU BLOC -->

            <div class="ms-auto">
                <a href="/admin" class="btn btn-outline-light btn-sm">Espace Admin</a>
            </div>
        </div>
    </nav>

    <!-- Contenu de la page -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">© {{ date('Y') }} Jangal CMS. Tous droits réservés.</p>
    </footer>
</body>

</html>