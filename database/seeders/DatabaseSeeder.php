<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Création un administrateur par défaut
        if (!Utilisateur::where('email', 'admin@un.cm')->exists()) {
            Utilisateur::create([
                'nom' => 'Admin',
                'prenom' => 'Principal',
                'email' => 'admin@un.cm',
                'mot_de_passe' => 'admin123',
                'role' => 'admin',
                'departement' => 'Administration',
                'centres_interet' => 'Gestion du réseau social',
            ]);
            $this->command->info('Utilisateur administrateur créé : admin@un.cm / admin123');
        }
    }
}