<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PageController; // <-- Vérifie que c'est importé
use App\Http\Controllers\FrontendController;

Auth::routes();

// --- ROUTES FRONTEND (PUBLIQUES) ---
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/post/{post:slug}', [FrontendController::class, 'show'])->name('post.show');

// LA ROUTE MANQUANTE EST ICI :
Route::get('/page/{page:slug}', [FrontendController::class, 'showPage'])->name('page.show');

// --- ROUTES ADMIN ---
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/plugins/{slug}/toggle', [AdminController::class, 'togglePlugin'])->name('admin.plugins.toggle');
    Route::post('/themes/{slug}/activate', [AdminController::class, 'activateTheme'])->name('admin.themes.activate');

    Route::resource('users', UserController::class)->names('admin.users');
    Route::resource('posts', PostController::class)->names('admin.posts');
    Route::resource('pages', PageController::class)->names('admin.pages');

    Route::get('/media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('admin.media.index');
    Route::post('/media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('admin.media.store');
    Route::delete('/media/{medium}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('admin.media.destroy');

    // Route pour l'upload TinyMCE (sans le middleware CSRF pour faciliter l'upload)
    Route::post('/media/upload', [\App\Http\Controllers\Admin\MediaController::class, 'uploadTinyMCE'])->name('admin.media.upload');
});
