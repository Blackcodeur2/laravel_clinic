<?php

namespace Database\Seeders;

use App\Models\Medicament;
use App\Models\Patient;
use App\Models\Role;
use App\Models\ServiceMedical;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $this->call(RoleSeeder::class);

        $adminRole = Role::where('nom', 'ADMIN')->first();
        $responsableRole = Role::where('nom', 'RESPONSABLE')->first();
        $caissierRole = Role::where('nom', 'CAISSIER')->first();

        // 2. Create default admin user
        User::firstOrCreate(['email' => 'admin@myclinic.com'], [
            'nom' => 'Admin',
            'prenom' => 'Super',
            'username' => 'admin',
            'email' => 'admin@myclinic.com',
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // 3. Create default responsible
        User::firstOrCreate(['email' => 'medecin@myclinic.com'], [
            'nom' => 'Dupont',
            'prenom' => 'Julien',
            'username' => 'medecin',
            'email' => 'medecin@myclinic.com',
            'role_id' => $responsableRole->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // 4. Create default caissier
        User::firstOrCreate(['email' => 'caissier@myclinic.com'], [
            'nom' => 'Martin',
            'prenom' => 'Sophie',
            'username' => 'caissier',
            'email' => 'caissier@myclinic.com',
            'role_id' => $caissierRole->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // 5. Services médicaux
        $services = [
            ['code' => 'CONS-GEN', 'nom' => 'Consultation générale', 'prix' => 5000],
            ['code' => 'CONS-SPE', 'nom' => 'Consultation spécialisée', 'prix' => 10000],
            ['code' => 'ECHO-ABD', 'nom' => 'Échographie abdominale', 'prix' => 25000],
            ['code' => 'ECG', 'nom' => 'Électrocardiogramme', 'prix' => 15000],
            ['code' => 'RADIO-TX', 'nom' => 'Radiographie thoracique', 'prix' => 12000],
            ['code' => 'PRISE-SANG', 'nom' => 'Prise de sang', 'prix' => 3000],
            ['code' => 'ANALYSE-URI', 'nom' => 'Analyse urinaire', 'prix' => 2000],
            ['code' => 'INJECTION', 'nom' => 'Injection', 'prix' => 1500],
            ['code' => 'PANS-SIMP', 'nom' => 'Pansement simple', 'prix' => 2500],
            ['code' => 'PANS-COMP', 'nom' => 'Pansement complexe', 'prix' => 5000],
        ];

        foreach ($services as $service) {
            ServiceMedical::firstOrCreate(['code' => $service['code']], $service);
        }

        // 6. Médicaments
        $medicaments = [
            ['nom' => 'Paracétamol 500mg', 'prix' => 500, 'stock' => 200, 'description' => 'Antalgique et antipyrétique'],
            ['nom' => 'Ibuprofène 400mg', 'prix' => 800, 'stock' => 150, 'description' => 'Anti-inflammatoire non stéroïdien'],
            ['nom' => 'Amoxicilline 500mg', 'prix' => 1500, 'stock' => 100, 'description' => 'Antibiotique à large spectre'],
            ['nom' => 'Metformine 850mg', 'prix' => 1200, 'stock' => 80, 'description' => 'Antidiabétique oral'],
            ['nom' => 'Amlodipine 5mg', 'prix' => 2000, 'stock' => 60, 'description' => 'Antihypertenseur'],
            ['nom' => 'Oméprazole 20mg', 'prix' => 1800, 'stock' => 120, 'description' => 'Inhibiteur de la pompe à protons'],
        ];

        foreach ($medicaments as $med) {
            Medicament::firstOrCreate(['nom' => $med['nom']], $med);
        }

        // 7. Demo patients
        Patient::factory(20)->create();
    }
}
