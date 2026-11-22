# ⚡ Jangal CMS

**Jangal CMS** est un Système de Gestion de Contenu (CMS) moderne, rapide et hautement extensible, construit sur le framework **Laravel**. Inspiré de WordPress, il a été pensé pour offrir une expérience utilisateur simple tout en offrant aux développeurs une architecture modulaire puissante basée sur un système de "Hooks".

---

## ✨ Fonctionnalités Principales

- **Gestion de contenu** : Créez, modifiez et publiez des Articles et des Pages avec un éditeur de texte WYSIWYG intégré (TinyMCE).
- **Médiathèque (Media Library)** : Uploadez et gérez vos images, vidéos et PDF de manière centralisée. Insérez-les facilement dans vos articles via l'éditeur ou définissez-les comme image de couverture.
- **Gestion des Utilisateurs** : Système d'authentification complet avec gestion des rôles (Administrateur et Éditeur).
- **Système de Plugins** : Étendez les fonctionnalités du CMS de manière dynamique sans toucher au code source.
- **Gestion des Thèmes** : Changez l'apparence de votre site en un clic. L'administration permet d'uploader, modifier et éditer directement le code des thèmes.
- **Tags** : Organisez vos articles avec un système de mots-clés (relation Many-to-Many).
- **Sécurité** : Protection des routes d'administration, protection CSRF, et validations strictes des données entrantes.

---

## 🚀 Installation

L'installation se fait en quelques minutes via un assistant graphique.

### Prérequis

- PHP >= 7.4 (Extensions : PDO, Mbstring, OpenSSL, Tokenizer, XML, Ctype, JSON)
- Composer
- Un serveur de base de données (MySQL / MariaDB)
- Un serveur web (Apache, Nginx ou WAMP/XAMPP)

### Étapes d'installation

1. Clonez le dépôt et installez les dépendances PHP :
    ```bash
    composer install
    ```

Assurez-vous que le dossier storage et bootstrap/cache sont inscriptibles.
Lancez votre serveur web local (ex: php artisan serve ou WAMP).
Rendez-vous sur http://localhost:8000/install.

Suivez les 3 étapes de l'assistant :

Vérification des prérequis serveur.
Configuration de la base de données.

Création de votre compte Administrateur (la base de données et le thème par défaut seront configurés automatiquement).

🔌 Comment créer un Plugin
Les plugins permettent d'ajouter de nouvelles fonctionnalités (ex: un système de contact, un SEO, etc.). Ils sont stockés dans le dossier platform/plugins/.

Structure d'un plugin
Créez un dossier platform/plugins/mon-super-plugin/ contenant :

plugin.json (Le manifeste) :

json
{
    "name": "Mon Super Plugin",
    "description": "Ajoute des fonctionnalités géniales.",
    "version": "1.0.0",
    "author": "Votre Nom"
}
Providers/PluginServiceProvider.php (Le point d'entrée) :

php
<?php

namespace Platform\Plugins\MonSuperPlugin\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Utilisez les Hooks pour interagir avec le CMS
        add_action('admin_dashboard_top', function() {
            echo '<div class="alert alert-info">Mon plugin est actif !</div>';
        });

        add_filter('post_content', function($content) {
            return $content . '<p>Ajouté par mon plugin.</p>';
        });
    }
}
Une fois le dossier créé, allez dans Admin > Tableau de bord pour activer votre plugin.

🎨 Comment créer un Thème
Les thèmes contrôlent l'apparence de la partie publique du site. Ils sont stockés dans platform/themes/.

Structure d'un thème
Créez un dossier platform/themes/mon-super-theme/ contenant :

theme.json (Le manifeste) :

json
{
    "name": "Mon Super Thème",
    "description": "Un thème moderne et responsive.",
    "version": "1.0.0",
    "author": "Votre Nom",
    "options": {
        "primary_color": "#0d6efd"
    }
}
views/layouts/master.blade.php (Le layout principal) :

html
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mon Site')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>Mon Menu</header>
    <main>@yield('content')</main>
    <footer>Mon Footer</footer>
</body>
</html>
views/welcome.blade.php (La page d'accueil) :

blade
@extends('theme::layouts.master')

@section('title', 'Accueil')

@section('content')
    <h1>Bienvenue sur mon thème personnalisé !</h1>
    @foreach($posts as $post)
        <article>
            <h2>{{ $post->title }}</h2>
            <div>{!! $post->content !!}</div>
        </article>
    @endforeach
@endsection
⚠️ Important : Pour utiliser le layout principal dans vos vues, vous devez toujours utiliser le namespace theme:: (ex: @extends('theme::layouts.master')). Cela permet au CMS d'isoler les vues du thème de celles du noyau Laravel.

Une fois le dossier créé, allez dans Admin > Thèmes pour l'activer.

⚙️ Architecture (Sous le capot)
Noyau (Core) : Situé dans app/Core/, il gère le chargement dynamique des extensions (plugins et thèmes) sans altérer le code source.

Hooks (Actions & Filtres) : Le CMS intègre un système de Hooks similaire à WordPress (add_action, do_action, add_filter, apply_filters).

Surcharge de vues : Les thèmes peuvent surcharger les vues des plugins en utilisant le dossier views/vendor/.

Installation Lock : Un fichier storage/installed.lock empêche l'accès à l'installateur une fois le CMS configuré.
```
