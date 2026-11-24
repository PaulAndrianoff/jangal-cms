<?php

use Illuminate\Support\Facades\Route;

// Inclusion manuelle (pour éviter les bugs d'autoload avec le tiret)
require_once base_path('platform/plugins/theme-options/Controllers/OptionsController.php');

use Platform\Plugins\ThemeOptions\Controllers\OptionsController;

Route::middleware(['web', 'auth', 'is_admin'])->prefix('admin/theme-options')->group(function () {
    Route::get('/', [OptionsController::class, 'index'])->name('admin.theme-options.index');
    Route::post('/', [OptionsController::class, 'update'])->name('admin.theme-options.update');
    Route::get('/schema', [OptionsController::class, 'editSchema'])->name('admin.theme-options.schema');
    Route::post('/schema', [OptionsController::class, 'updateSchema'])->name('admin.theme-options.schema.update');
});
