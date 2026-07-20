<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('utilisateurs')->insertBatch([
            ['nom' => 'Rojo',    'email' => 'rojo@itu.mg',    'mot_de_passe' => 'secret123', 'role' => 'admin',  'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Sitraka', 'email' => 'sitraka@itu.mg', 'mot_de_passe' => 'azerty',    'role' => 'membre', 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Tiana',   'email' => 'tiana@itu.mg',   'mot_de_passe' => 'password',  'role' => 'membre', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->db->table('articles')->insertBatch([
            ['titre' => 'Bienvenue sur le blog',   'contenu' => 'Premier article de demonstration.', 'utilisateur_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['titre' => 'Decouvrir CodeIgniter 4', 'contenu' => 'Un framework PHP leger.',            'utilisateur_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['titre' => 'SQLite en developpement', 'contenu' => 'Base embarquee, pratique.',          'utilisateur_id' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
