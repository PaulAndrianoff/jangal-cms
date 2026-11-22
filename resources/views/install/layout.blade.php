<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Installation de Jangal CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .install-card {
            width: 100%;
            max-width: 700px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            font-weight: bold;
            color: #adb5bd;
        }

        .step-indicator .active {
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <div class="card install-card">
        <div class="card-header bg-dark text-white text-center py-3">
            <h4 class="mb-0">⚡ Jangal CMS - Assistant d'installation</h4>
        </div>
        <div class="card-body p-4">
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="step-indicator">
                <span class="{{ request()->routeIs('install.index') ? 'active' : '' }}">1. Prérequis</span>
                <span class="{{ request()->routeIs('install.database*') ? 'active' : '' }}">2. Base de données</span>
                <span class="{{ request()->routeIs('install.admin*') ? 'active' : '' }}">3. Administrateur</span>
                <span class="{{ request()->routeIs('install.finish') ? 'active' : '' }}">4. Terminé</span>
            </div>

            @yield('content')
        </div>
    </div>
</body>

</html>