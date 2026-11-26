<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Page;
use App\Models\Tag;
use App\Models\User;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $admin = User::first();

        // 1. Créer des tags
        $tag1 = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $tag2 = Tag::create(['name' => 'Design', 'slug' => 'design']);

        // 2. Créer une page
        Page::create([
            'title' => 'À propos',
            'slug' => 'a-propos',
            'content' => '<h2>Bienvenue sur Jangal CMS</h2><p>Ceci est une page de démonstration créée automatiquement.</p>',
            'status' => 'published'
        ]);

        // 3. Créer des articles
        $post1 = Post::create([
            'user_id' => $admin->id,
            'title' => 'Bienvenue sur votre nouveau CMS',
            'slug' => 'bienvenue',
            'content' => '<p>Félicitations ! Votre CMS est installé. Vous pouvez commencer à rédiger vos articles.</p>',
            'status' => 'published'
        ]);
        $post1->tags()->attach([$tag1->id, $tag2->id]);

        $post2 = Post::create([
            'user_id' => $admin->id,
            'title' => 'Comment personnaliser votre thème',
            'slug' => 'personnaliser-theme',
            'content' => '<p>Rendez-vous dans l\'administration pour changer de thème en un clic.</p>',
            'status' => 'published'
        ]);
        $post2->tags()->attach([$tag2->id]);
        
        // Activer le thème par défaut
        \DB::table('themes')->insert([
            'name' => 'modern',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}