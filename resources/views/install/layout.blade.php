<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Installation - Jangal CMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .install-card { width: 100%; max-width: 650px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: none; border-radius: 16px; background: white; overflow: hidden; }
        .install-header { background: #1a202c; color: white; padding: 30px; text-align: center; }
        .install-header h2 { margin: 0; font-weight: 700; letter-spacing: -1px; }
        .install-body { padding: 40px; }
        
        /* Stepper Progress */
        .stepper { display: flex; justify-content: space-between; margin-bottom: 30px; position: relative; }
        .stepper::before { content: ''; position: absolute; top: 15px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1; }
        .step { position: relative; z-index: 2; text-align: center; background: white; padding: 0 10px; }
        .step-circle { width: 30px; height: 30px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; margin: 0 auto 5px; font-weight: 600; font-size: 0.8rem; }
        .step.active .step-circle { background: #3b82f6; color: white; box-shadow: 0 0 0 4px #dbeafe; }
        .step-label { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        .step.active .step-label { color: #3b82f6; }

        .btn-primary { background-color: #3b82f6; border: none; padding: 12px; border-radius: 8px; font-weight: 600; }
        .form-control { padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px #dbeafe; }
    </style>
</head>
<body>
    <div class="card install-card">
        <div class="install-header">
            <h2>⚡ Jangal CMS</h2>
            <p class="mb-0 text-muted mt-2">Assistant d'installation</p>
        </div>
        <div class="install-body">
            
            <div class="stepper">
                <div class="step {{ request()->routeIs('install.index') ? 'active' : '' }}">
                    <div class="step-circle">1</div>
                    <div class="step-label">Serveur</div>
                </div>
                <div class="step {{ request()->routeIs('install.database*') ? 'active' : '' }}">
                    <div class="step-circle">2</div>
                    <div class="step-label">Base de données</div>
                </div>
                <div class="step {{ request()->routeIs('install.admin*') ? 'active' : '' }}">
                    <div class="step-circle">3</div>
                    <div class="step-label">Administrateur</div>
                </div>
                <div class="step {{ request()->routeIs('install.finish') ? 'active' : '' }}">
                    <div class="step-circle">4</div>
                    <div class="step-label">Terminé</div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>