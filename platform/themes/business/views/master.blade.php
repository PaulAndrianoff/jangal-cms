<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Business Pro')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #0f172a;
            --accent-color: #3b82f6;
            --light-bg: #f8fafc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #334155;
        }

        h1,
        h2,
        h3,
        h4 {
            color: #0f172a;
            font-weight: 700;
        }

        /* --- HEADER BUSINESS --- */
                /* --- HEADER BUSINESS --- */
        header {
            background-color: var(--primary-color);
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
        }

        .header-widgets {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .header-widgets .card { background: transparent !important; border: none !important; box-shadow: none !important; margin: 0 !important; }
        .header-widgets .card-body { padding: 0 !important; }
        .header-widgets h5 { display: none !important; }

        /* Le menu (Texte blanc) */
        .header-widgets ul { display: flex; gap: 1.5rem; list-style: none; padding: 0; margin: 0; }
        .header-widgets ul li a { color: white !important; text-transform: uppercase; font-size: 0.85rem; font-weight: 500; text-decoration: none; }
        .header-widgets ul li a:hover { color: var(--accent-color) !important; }
        
        /* La barre de recherche (Texte blanc) */
        .header-widgets form { display: flex; border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 5px; margin: 0; width: 200px; }
        .header-widgets input { border: none !important; background: transparent !important; color: white !important; box-shadow: none !important; }
        .header-widgets input::placeholder { color: rgba(255,255,255,0.5); }
        .header-widgets button { background: transparent !important; border: none !important; color: var(--accent-color) !important; font-weight: bold; box-shadow: none !important; }

        /* Le bouton Admin personnalisé */
        header a[href="/admin"] {
            border-radius: 50px !important;
            border: 1px solid white !important;
            color: white !important;
            background: transparent !important;
            transition: 0.3s;
        }
        header a[href="/admin"]:hover {
            background: white !important;
            color: var(--primary-color) !important;
        }

        /* Hero */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e293b 100%);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }

        .hero h1 {
            color: white;
            font-size: 3.5rem;
            letter-spacing: -1px;
        }

        /* Boutons */
        .btn-accent {
            background-color: var(--accent-color);
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: 0.3s;
            border: none;
        }

        .btn-accent:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
            color: white;
        }

        /* Cards */
        .card-post {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.4s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .card-post:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: #94a3b8;
            padding: 60px 0 30px;
            margin-top: 80px;
        }
    </style>
    {!! do_action('theme_head') !!}
</head>

<body>
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

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="text-white mb-4">{!! do_action('theme_logo') !!}</h4>
                    <p>Votre partenaire pour des solutions digitales innovantes.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Accueil</a></li>
                        <li><a href="/admin" class="text-secondary text-decoration-none">Administration</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    {!! render_widget_area('footer') !!}
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <p class="text-center mb-0 small">© {{ date('Y') }} Business Pro. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>