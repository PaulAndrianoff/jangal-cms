<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Minimalist')</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fafafa;
            color: #1a1a1a;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        h4,
        .serif {
            font-family: 'Playfair Display', serif;
        }

        /* 3. Le conteneur du formulaire : une simple ligne fine */
        /* --- HEADER MINIMALIST PRO (Centré) --- */
                /* --- HEADER MINIMALIST --- */
        header {
            background-color: #fafafa;
            padding: 20px 0;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
        }

        /* On force l'alignement horizontal des widgets */
        .header-widgets {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .header-widgets .card { background: transparent !important; border: none !important; box-shadow: none !important; margin: 0 !important; }
        .header-widgets .card-body { padding: 0 !important; }
        .header-widgets h5 { display: none !important; }

        /* Le menu du widget */
        .header-widgets ul { display: flex; gap: 1.5rem; list-style: none; padding: 0; margin: 0; }
        .header-widgets ul li a { color: #1a1a1a !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; text-decoration: none; }
        
        /* La barre de recherche */
        .header-widgets form { display: flex; border-bottom: 1px solid #1a1a1a; padding-bottom: 2px; margin: 0; width: 180px; }
        .header-widgets input { border: none !important; background: transparent !important; font-family: 'Playfair Display', serif; font-style: italic; color: #1a1a1a; box-shadow: none !important; }
        .header-widgets button { background: transparent !important; border: none !important; color: #1a1a1a !important; font-weight: bold; font-size: 0.7rem; box-shadow: none !important; }

        /* Le bouton Admin personnalisé */
        header a[href="/admin"] {
            border-radius: 0 !important;
            border: 1px solid #1a1a1a !important;
            color: #1a1a1a !important;
            background: transparent !important;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 2px;
        }
        /* Le bouton GO (sans le bleu de Bootstrap) */
        .header-widgets button,
        .header-widgets .btn-primary {
            background: transparent !important;
            border: none !important;
            color: #1a1a1a !important;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 0 0 10px;
            box-shadow: none !important;
            cursor: pointer;
        }

        .header-widgets button:hover {
            color: #888 !important;
        }

        /* Grille Portfolio */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 2px;
            margin-top: 50px;
        }

        .portfolio-item {
            position: relative;
            overflow: hidden;
            display: block;
            background: #000;
            aspect-ratio: 4/5;
        }

        .portfolio-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
            transition: transform 0.7s ease, opacity 0.7s ease;
        }

        .portfolio-item:hover img {
            transform: scale(1.05);
            opacity: 1;
        }

        .portfolio-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
            opacity: 0;
            transition: 0.4s;
        }

        .portfolio-item:hover .portfolio-text {
            opacity: 1;
        }

        /* Typographie */
        .lead-text {
            font-size: 1.5rem;
            font-weight: 300;
            line-height: 1.6;
            color: #555;
        }

        /* Footer */
        footer {
            padding: 80px 0 40px;
            border-top: 1px solid #e5e5e5;
            margin-top: 100px;
        }
    </style>
    {!! do_action('theme_head') !!}
</head>

<body>
    <!-- Header -->
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

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <h2 class="serif mb-4">Créons ensemble.</h2>
            <p class="text-muted mb-5">Disponible pour des collaborations et des projets freelance.</p>
            {!! render_widget_area('footer') !!}
            <p class="text-muted small mt-5">&copy; {{ date('Y') }} Studio Minimalist. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>