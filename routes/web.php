<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;

// 2. AJOUTE CETTE LIGNE (elle génère /login, /logout, /register, etc.)
Auth::routes(); 

// La route pour la page d'accueil
use App\Http\Controllers\FrontendController;

// Page d'accueil
Route::get('/', [FrontendController::class, 'index'])->name('home');
// Route::get('/home', [FrontendController::class, 'index'])->name('home');

// Page d'un article seul (grâce au slug)
Route::get('/post/{post:slug}', [FrontendController::class, 'show'])->name('post.show');

// Les routes pour l'administration
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/plugins/{slug}/toggle', [AdminController::class, 'togglePlugin'])->name('admin.plugins.toggle');
    Route::post('/themes/{slug}/activate', [AdminController::class, 'activateTheme'])->name('admin.themes.activate');
    Route::resource('users', UserController::class)->names('admin.users');
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class)->names('admin.posts');
});