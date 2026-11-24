<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InstallController;

Auth::routes();

// --- ROUTES FRONTEND (PUBLIQUES) ---
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/post/{post:slug}', [FrontendController::class, 'show'])->name('post.show');

// LA ROUTE MANQUANTE EST ICI :
Route::get('/page/{page:slug}', [FrontendController::class, 'showPage'])->name('page.show');
Route::get('/tag/{tag:slug}', [App\Http\Controllers\FrontendController::class, 'tag'])->name('tag.show');

// --- ROUTES ADMIN ---
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/plugins/{slug}/toggle', [AdminController::class, 'togglePlugin'])->name('admin.plugins.toggle');
    Route::post('/themes/{slug}/activate', [AdminController::class, 'activateTheme'])->name('admin.themes.activate');

    // Gestion des utilisateurs, articles et pages
    Route::resource('users', UserController::class)->names('admin.users');
    Route::resource('posts', PostController::class)->names('admin.posts');
    Route::resource('pages', PageController::class)->names('admin.pages');

    // Gestion des médias
    Route::get('/media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('admin.media.index');
    Route::post('/media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('admin.media.store');
    Route::delete('/media/{medium}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('admin.media.destroy');
    Route::get('/media/list', [\App\Http\Controllers\Admin\MediaController::class, 'list'])->name('admin.media.list');

    // Route pour l'upload TinyMCE (sans le middleware CSRF pour faciliter l'upload)
    Route::post('/media/upload', [\App\Http\Controllers\Admin\MediaController::class, 'uploadTinyMCE'])->name('admin.media.upload');

    // Gestion des thèmes
    Route::get('/themes', [\App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('admin.themes.index');
    Route::post('/themes/upload', [\App\Http\Controllers\Admin\ThemeController::class, 'upload'])->name('admin.themes.upload');
    Route::post('/themes/{slug}/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('admin.themes.activate');
    Route::get('/themes/{slug}/edit', [\App\Http\Controllers\Admin\ThemeController::class, 'edit'])->name('admin.themes.edit');
    Route::put('/themes/{slug}', [\App\Http\Controllers\Admin\ThemeController::class, 'update'])->name('admin.themes.update');
    Route::delete('/themes/{slug}', [\App\Http\Controllers\Admin\ThemeController::class, 'destroy'])->name('admin.themes.destroy');

    // Édition des fichiers du thème
    Route::get('/themes/{slug}/files', [\App\Http\Controllers\Admin\ThemeController::class, 'files'])->name('admin.themes.files');
    Route::get('/themes/{slug}/files/edit', [\App\Http\Controllers\Admin\ThemeController::class, 'editFile'])->name('admin.themes.editFile');
    Route::post('/themes/{slug}/files/update', [\App\Http\Controllers\Admin\ThemeController::class, 'updateFile'])->name('admin.themes.updateFile');

    // Gestion des widgets
    Route::get('/widgets', [\App\Http\Controllers\Admin\WidgetController::class, 'index'])->name('admin.widgets.index');
    Route::post('/widgets', [\App\Http\Controllers\Admin\WidgetController::class, 'store'])->name('admin.widgets.store');
    Route::delete('/widgets/{widget}', [\App\Http\Controllers\Admin\WidgetController::class, 'destroy'])->name('admin.widgets.destroy');

    // Routes pour l'édition des paramètres d'un widget
    Route::get('/widgets/{widget}/settings', [\App\Http\Controllers\Admin\WidgetController::class, 'edit'])->name('admin.widgets.edit');
    Route::put('/widgets/{widget}/settings', [\App\Http\Controllers\Admin\WidgetController::class, 'update'])->name('admin.widgets.update');

    // Gestion des plugins
    Route::get('/plugins', [\App\Http\Controllers\Admin\PluginController::class, 'index'])->name('admin.plugins.index');
    Route::post('/plugins/upload', [\App\Http\Controllers\Admin\PluginController::class, 'upload'])->name('admin.plugins.upload');
    Route::get('/plugins/{slug}/edit', [\App\Http\Controllers\Admin\PluginController::class, 'edit'])->name('admin.plugins.edit');
    Route::put('/plugins/{slug}', [\App\Http\Controllers\Admin\PluginController::class, 'update'])->name('admin.plugins.update');
    Route::post('/plugins/{slug}/toggle', [\App\Http\Controllers\Admin\PluginController::class, 'toggle'])->name('admin.plugins.toggle');
    Route::delete('/plugins/{slug}', [\App\Http\Controllers\Admin\PluginController::class, 'destroy'])->name('admin.plugins.destroy');

    // Éditeur de fichiers JSON
    Route::get('/plugins/{slug}/json', [\App\Http\Controllers\Admin\PluginController::class, 'editJson'])->name('admin.plugins.json');
    Route::post('/plugins/{slug}/json', [\App\Http\Controllers\Admin\PluginController::class, 'updateJson'])->name('admin.plugins.json.update');
});

// Routes d'installation
Route::middleware([\App\Http\Middleware\RedirectIfInstalled::class])->group(function () {
    Route::get('/install', [InstallController::class, 'index'])->name('install.index');
    Route::get('/install/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/install/database', [InstallController::class, 'storeDatabase'])->name('install.database.store');
    Route::get('/install/admin', [InstallController::class, 'admin'])->name('install.admin');
    Route::post('/install/admin', [InstallController::class, 'storeAdmin'])->name('install.admin.store');
    Route::get('/install/finish', [InstallController::class, 'finish'])->name('install.finish');
});
