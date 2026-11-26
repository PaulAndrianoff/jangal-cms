# Jangal CMS

Jangal CMS est un système de gestion de contenu (CMS) moderne, rapide et hautement extensible, construit sur le framework Laravel.

Inspiré de WordPress, il offre une expérience utilisateur simple tout en fournissant aux développeurs une architecture modulaire puissante basée sur un système de hooks et de widgets.

## Fonctionnalités principales

- **Gestion de contenu** : créez, modifiez et publiez des articles et des pages avec un éditeur de texte WYSIWYG intégré (TinyMCE).
- **Médiathèque** : téléversez et gérez vos images, vidéos et PDF de manière centralisée. Insérez-les facilement dans vos articles ou utilisez-les comme image de couverture.
- **Gestion des utilisateurs** : authentification complète avec gestion des rôles Administrateur et Éditeur.
- **Système de plugins** : étendez les fonctionnalités du CMS de manière dynamique sans modifier le code source. Le CMS gère le téléversement ZIP, l'activation, l'édition et les migrations automatiques des plugins.
- **Gestion des thèmes** : changez l'apparence du site en un clic. Téléversez des thèmes, modifiez leurs manifestes et utilisez l'éditeur de code intégré basé sur CodeMirror depuis l'administration.
- **Système de widgets** : placez des blocs comme Menu, Formulaire, Recherche ou Tags dans différentes zones définies par vos thèmes : Header, Sidebar, Footer.
- **Charte graphique** : modifiez les couleurs, polices et logos du thème. Les champs du formulaire sont entièrement dynamiques et configurables depuis l'administration.
- **Journal d'activité** : plugin natif qui trace les actions effectuées dans l'administration par les utilisateurs.

## Installation

L'installation se fait en quelques minutes grâce à l'assistant graphique intégré.

### Prérequis

- PHP >= 7.4
  - Extensions : PDO, Mbstring, OpenSSL, Tokenizer, XML, Ctype, JSON
- Composer
- Un serveur de base de données : MySQL ou MariaDB
- Un serveur web : Apache, Nginx ou WAMP/XAMPP

### Étapes

1. Décompressez les fichiers de Jangal CMS à la racine de votre serveur web.
2. Si le dossier `vendor` n'est pas disponible, ouvrez un terminal et lancez :

   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. Rendez-vous sur `http://votre-domaine.com/install`.
4. Suivez les quatre étapes de l'assistant :
   - Vérification des prérequis serveur.
   - Configuration de la base de données. Le script peut la créer automatiquement.
   - Création du compte Administrateur et choix d'installation des données de démonstration.
   - Installation terminée. Un fichier `storage/installed.lock` est généré pour sécuriser l'installation.

## Distribution du projet

Si vous empaquetez le CMS dans un fichier ZIP pour le distribuer, n'incluez pas les éléments suivants :

- `vendor/` : le client doit lancer `composer install`
- `node_modules/`
- `.env` : contient les identifiants
- `storage/installed.lock` : permet à l'installateur de se lancer chez le client
- `storage/logs/*`
- `storage/framework/cache/*`
- `storage/framework/views/*`
- `.git/`
- `.idea/` ou `.vscode/`

## Développement de plugins

Les plugins permettent d'ajouter de nouvelles fonctionnalités. Ils sont stockés dans `platform/plugins/`.

Un plugin peut créer ses propres tables, les migrer automatiquement et déclarer ses propres routes.

### Structure d'un plugin

Créez un dossier `platform/plugins/mon-super-plugin/` contenant :

#### `plugin.json`

Le manifeste du plugin :

```json
{
    "name": "Mon Super Plugin",
    "description": "Ajoute des fonctionnalités géniales.",
    "version": "1.0.0",
    "author": "Votre Nom"
}
```

#### `Providers/PluginServiceProvider.php`

Le point d'entrée du plugin :

```php
<?php

namespace Platform\Plugins\MonSuperPlugin\Providers;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Utilisez les hooks pour interagir avec le CMS
        add_action('admin_sidebar_links', function () {
            echo '<li class="nav-item"><a class="nav-link" href="/admin/mon-plugin">Mon Plugin</a></li>';
        });
    }
}
```

#### `database/migrations/`

Ce dossier est optionnel. Ajoutez-y vos fichiers de migration : ils seront exécutés automatiquement lors de l'activation du plugin.

## Développement de thèmes

Les thèmes contrôlent l'apparence de la partie publique. Ils sont stockés dans `platform/themes/`.

### Structure d'un thème

Créez un dossier `platform/themes/mon-super-theme/` contenant :

#### `theme.json`

Le manifeste du thème :

```json
{
    "name": "Mon Super Thème",
    "description": "Un thème moderne et responsive.",
    "version": "1.0.0",
    "author": "Votre Nom"
}
```

#### `views/layouts/master.blade.php`

Le layout principal :

```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mon Site')</title>
    <!-- Ajoutez vos CSS ici -->
    {!! do_action('theme_head') !!} <!-- Hook pour le Customizer -->
</head>
<body>
    <header>{!! do_action('theme_logo') !!}</header>

    <main>@yield('content')</main>

    <!-- Zone de widgets du footer -->
    <footer>{!! render_widget_area('footer') !!}</footer>
</body>
</html>
```

#### `views/welcome.blade.php`

La page d'accueil :

```blade
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
```

> **Important** : pour utiliser le layout principal dans vos vues, vous devez toujours utiliser le namespace `theme::`, par exemple `@extends('theme::layouts.master')`. Cela permet au CMS d'isoler les vues du thème de celles du noyau Laravel.

## Système de widgets

Les widgets sont des blocs de contenu réutilisables, comme les menus, la recherche ou les formulaires. Ils peuvent être placés dans les zones définies par vos thèmes, comme la sidebar ou le footer.

Un widget est enregistré via un plugin dans sa méthode `boot()` :

```php
app('widget')->register(
    'mon_widget',
    'Nom du Widget',
    function ($settings) {
        return '<div>Mon contenu de widget personnalisable</div>';
    },
    'chemin.vers.vue.de.parametres'
); // Le quatrième paramètre est optionnel
```

Pour afficher une zone dans un thème, utilisez le helper suivant :

```blade
{!! render_widget_area('sidebar') !!}
```

## Licence

Ce projet est sous licence MIT.

Développé avec ❤️ et Laravel.
