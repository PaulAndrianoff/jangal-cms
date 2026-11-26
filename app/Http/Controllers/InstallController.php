<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Core\Themes\ThemeManager;

class InstallController extends Controller
{
    public function index()
    {
        $requirements = [
            'php' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'pdo' => extension_loaded('pdo'),
            'mbstring' => extension_loaded('mbstring'),
            'openssl' => extension_loaded('openssl'),
            'tokenizer' => extension_loaded('tokenizer'),
            'xml' => extension_loaded('xml'),
            'ctype' => extension_loaded('ctype'),
            'json' => extension_loaded('json'),
            'storage' => is_writable(storage_path()),
            'bootstrap_cache' => is_writable(base_path('bootstrap/cache')),
            'env_writable' => is_writable(base_path('.env.example')) || is_writable(base_path('.env')),
        ];

        $allPassed = !in_array(false, $requirements);
        return view('install.step1', compact('requirements', 'allPassed'));
    }

    public function database()
    {
        return view('install.step2');
    }

    public function storeDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
            'db_password' => 'nullable',
        ]);

        try {
            // 1. On se connecte d'abord SANS le nom de la base pour pouvoir la créer
            $pdo = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port}",
                $request->db_user,
                $request->db_password
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // 2. On vérifie si la base existe, sinon on la crée
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$request->db_name}'");
            if ($stmt->rowCount() == 0) {
                $pdo->exec("CREATE DATABASE `{$request->db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de se connecter ou de créer la base de données. Vérifiez vos accès. Erreur : ' . $e->getMessage());
        }

        // 3. Mise à jour du fichier .env
        $this->updateEnv([
            'APP_NAME' => '"Jangal CMS"',
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'APP_URL' => url('/'),
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_name,
            'DB_USERNAME' => $request->db_user,
            'DB_PASSWORD' => '"' . $request->db_password . '"',
        ]);

        session(['install_db' => $request->only('db_host', 'db_port', 'db_name', 'db_user', 'db_password')]);

        return redirect()->route('install.admin');
    }

    public function admin()
    {
        return view('install.step3');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|confirmed',
            'install_demo' => 'nullable|boolean'
        ]);

        $db = session('install_db');
        config([
            'database.connections.mysql.host' => $db['db_host'],
            'database.connections.mysql.port' => $db['db_port'],
            'database.connections.mysql.database' => $db['db_name'],
            'database.connections.mysql.username' => $db['db_user'],
            'database.connections.mysql.password' => $db['db_password'],
        ]);

        try {
            // 1. Migrations
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            // 2. Clé d'application
            Artisan::call('key:generate', ['--force' => true]);

            // 3. Création Admin
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'is_admin' => true,
            ]);

            // 4. Données de démo (si coché)
            if ($request->has('install_demo')) {
                Artisan::call('db:seed', ['--class' => 'DemoSeeder', '--force' => true]);
            } else {
                // Si pas de démo, on active juste le thème par défaut
                app(ThemeManager::class)->activate('modern');
            }

            // 5. Fichier de verrouillage
            File::put(storage_path('installed.lock'), 'Installed on ' . date('Y-m-d H:i:s'));

            // 6. On vide le cache pour que le nouveau .env soit pris en compte
            Artisan::call('optimize:clear');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'installation : ' . $e->getMessage());
        }

        return redirect()->route('install.finish');
    }

    public function finish()
    {
        return view('install.step4');
    }

    // Fonction robuste pour le .env
    private function updateEnv(array $data)
    {
        $envFile = base_path('.env');
        
        // Si le .env n'existe pas, on le crée à partir du .env.example
        if (!file_exists($envFile)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envFile);
            } else {
                File::put($envFile, "");
            }
        }

        $content = File::get($envFile);
        
        foreach ($data as $key => $value) {
            // Si la clé existe, on la remplace
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                // Sinon on l'ajoute
                $content .= "\n{$key}={$value}";
            }
        }
        
        File::put($envFile, $content);
    }
}