<?php

namespace Platform\Plugins\ActivityLogger\Controllers;

use App\Http\Controllers\Controller;
use Platform\Plugins\ActivityLogger\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('activity-logger::index', compact('logs'));
    }

    public function settings()
    {
        $configPath = base_path('platform/plugins/activity-logger/config.json');
        $config = json_decode(File::get($configPath), true);
        return view('activity-logger::settings', compact('config'));
    }

    public function updateSettings(Request $request)
    {
        $config = [
            'log_auth' => $request->has('log_auth'),
            'log_posts' => $request->has('log_posts'),
            'log_pages' => $request->has('log_pages'),
            'log_users' => $request->has('log_users'),
            'log_media' => $request->has('log_media'),
        ];

        $configPath = base_path('platform/plugins/activity-logger/config.json');
        File::put($configPath, json_encode($config, JSON_PRETTY_PRINT));

        return back()->with('success', 'Paramètres du journal mis à jour.');
    }
}