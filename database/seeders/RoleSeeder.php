<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nom' => 'ADMIN', 'description' => 'Administrateur système – accès total'],
            ['nom' => 'RESPONSABLE', 'description' => 'Responsable médical – gestion des consultations'],
            ['nom' => 'CAISSIER', 'description' => 'Caissier – gestion de la facturation et des paiements'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nom' => $role['nom']], $role);
        }
    }
}
