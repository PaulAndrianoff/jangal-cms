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
    // Étape 1 : Vérification des prérequis (extensions PHP, permissions)
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
        ];

        $allPassed = !in_array(false, $requirements);

        return view('install.step1', compact('requirements', 'allPassed'));
    }

    // Étape 2 : Formulaire de connexion à la base de données
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

        // Test de la connexion
        try {
            $pdo = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_name}",
                $request->db_user,
                $request->db_password
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de se connecter à la base de données. Vérifiez vos informations. Erreur : ' . $e->getMessage());
        }

        // Si ça marche, on écrit dans le .env
        $this->updateEnv([
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_name,
            'DB_USERNAME' => $request->db_user,
            'DB_PASSWORD' => $request->db_password,
        ]);

        // On met les infos en session pour l'étape suivante
        session(['install_db' => $request->only('db_host', 'db_port', 'db_name', 'db_user', 'db_password')]);

        return redirect()->route('install.admin');
    }

    // Étape 3 : Formulaire de création du compte Admin
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
        ]);

        // On modifie les variables de config en mémoire pour la migration
        $db = session('install_db');
        config([
            'database.connections.mysql.host' => $db['db_host'],
            'database.connections.mysql.port' => $db['db_port'],
            'database.connections.mysql.database' => $db['db_name'],
            'database.connections.mysql.username' => $db['db_user'],
            'database.connections.mysql.password' => $db['db_password'],
        ]);

        // 1. Lancer les migrations
        Artisan::call('migrate:fresh', ['--force' => true]);

        // 2. Créer l'utilisateur Admin
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => true,
        ]);

        // 3. Activer le thème par défaut
        try {
            app(ThemeManager::class)->activate('modern');
        } catch (\Exception $e) {}

        // 4. Créer le fichier de verrouillage
        File::put(storage_path('installed.lock'), 'Installed on ' . date('Y-m-d H:i:s'));

        return redirect()->route('install.finish');
    }

    public function finish()
    {
        return view('install.step4');
    }

    // Fonction pour modifier le fichier .env
    private function updateEnv(array $data)
    {
        $envFile = base_path('.env');
        if (file_exists($envFile)) {
            $content = File::get($envFile);
            foreach ($data as $key => $value) {
                // Si la clé existe déjà dans le .env, on la remplace
                if (preg_match("/^{$key}=.*/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
                } else {
                    // Sinon on l'ajoute à la fin
                    $content .= "\n{$key}=\"{$value}\"";
                }
            }
            File::put($envFile, $content);
        }
    }
}