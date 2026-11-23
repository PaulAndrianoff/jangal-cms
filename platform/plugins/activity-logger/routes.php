<?php

use Illuminate\Support\Facades\Route;

// On force l'inclusion du contrôleur pour contourner l'autoload de Composer
require_once base_path('platform/plugins/activity-logger/Controllers/LogController.php');
require_once base_path('platform/plugins/activity-logger/Models/ActivityLog.php');

use Platform\Plugins\ActivityLogger\Controllers\LogController;

Route::middleware(['web', 'auth', 'is_admin'])->prefix('admin/logs')->group(function () {
    Route::get('/', [LogController::class, 'index'])->name('admin.logs.index');
    Route::get('/settings', [LogController::class, 'settings'])->name('admin.logs.settings');
    Route::post('/settings', [LogController::class, 'updateSettings'])->name('admin.logs.settings.update');
});