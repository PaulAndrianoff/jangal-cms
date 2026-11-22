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
    <!-- Zone Header -->
    <header>
        {!! render_widget_area('header') !!}
    </header>

    <div class="container mt-4">
        <div class="row">
            <!-- Contenu Principal -->
            <main class="col-md-8">
                @yield('content')
            </main>

            <!-- Zone Sidebar (Colonne de droite) -->
            <aside class="col-md-4">
                {!! render_widget_area('sidebar') !!}
            </aside>
        </div>
    </div>

    <!-- Zone Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        {!! render_widget_area('footer') !!}
        <p class="mb-0 mt-3">© {{ date('Y') }} Jangal CMS.</p>
    </footer>
</body>

</html>