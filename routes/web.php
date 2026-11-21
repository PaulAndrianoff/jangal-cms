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
    Route::resource('pages', PageController::class)->names('admin.pages'); // <-- Vérifie que c'est là
});