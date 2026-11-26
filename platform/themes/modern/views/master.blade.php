<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jangal CMS - Le Blog Moderne')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #6366f1;
            --text-color: #1e293b;
            --bg-color: #f8fafc;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            -webkit-font-smoothing: antialiased;
        }

        /* --- HEADER MODERN --- */
        header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 20px 0;
            position: sticky;
            display: flex;
            /* Garde le header fixé en haut lors du scroll */
            top: 0;
            z-index: 1000;
        }

        .header-widgets {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .header-widgets .card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
        }

        .header-widgets .card-body {
            padding: 0 !important;
        }

        .header-widgets h5 {
            display: none !important;
        }

        /* Le menu */
        .header-widgets ul {
            display: flex;
            gap: 1.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .header-widgets ul li a {
            color: #1e293b !important;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .header-widgets ul li a:hover {
            color: var(--primary-color) !important;
        }

        /* La barre de recherche */
        .header-widgets form {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 50px;
            padding: 5px 15px;
            margin: 0;
            width: 220px;
        }

        .header-widgets input {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            font-size: 0.9rem;
        }

        .header-widgets button {
            background: transparent !important;
            border: none !important;
            color: var(--primary-color) !important;
            box-shadow: none !important;
            padding: 0 0 0 5px;
        }

        /* Le bouton Admin personnalisé */
        header a[href="/admin"] {
            border-radius: 50px !important;
            border: 1px solid #cbd5e1 !important;
            color: #1e293b !important;
            background: white !important;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        header a[href="/admin"]:hover {
            background: #f8fafc !important;
        }

        /* Cartes Articles Modernes */
        .post-card {
            border: none;
            border-radius: 20px;
            background: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .post-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
        }

        .post-card img {
            border-radius: 20px 20px 0 0;
            transition: transform 0.5s;
        }

        .post-card:hover img {
            transform: scale(1.03);
        }

        /* Typographie */
        .hero-title {
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.1;
        }

        .content-html {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #334155;
        }

        .content-html img {
            border-radius: 12px;
            max-width: 100%;
            height: auto;
        }

        .content-html h2,
        .content-html h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        /* Boutons */
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px var(--primary-color);
        }

        /* Widgets Sidebar */
        .sidebar-widget {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
    </style>
    {!! do_action('theme_head') !!}
</head>

<body>
    <!-- Navbar -->
    <header>
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <a class="navbar-brand text-decoration-none" href="{{ route('home') }}">
                {!! do_action('theme_logo') !!}
            </a>
        </div>
        <div class="container d-flex justify-content-center mt-3">
            <div class="header-widgets">
                {!! render_widget_area('header') !!}
            </div>
            <a href="/admin" class="btn btn-outline-dark btn-sm ms-lg-4 rounded-pill px-3">Admin</a>
        </div>
    </header>

    <!-- Contenu -->
    <main class="py-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">{!! do_action('theme_logo') !!}</h5>
                    <p class="text-muted small">Le meilleur CMS propulsé par Laravel pour vos projets digitaux.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold mb-3">Liens utiles</h6>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Accueil</a></li>
                        <li class="mb-2"><a href="/admin" class="text-muted text-decoration-none">Administration</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    {!! render_widget_area('footer') !!}
                </div>
            </div>
            <hr class="text-black-50">
            <p class="text-center text-muted small mb-0">© {{ date('Y') }} Jangal CMS. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>