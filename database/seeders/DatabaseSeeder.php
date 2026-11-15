<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

/**
 * Données de démonstration du prototype.
 * Producteurs, textes et photos : repris du site actuel. Adhérents et montants : fictifs.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Aucun e-mail réel pendant le remplissage de la base.
        Mail::fake();

        $this->call([
            RoleSeeder::class,
            PageSeeder::class,
            DemoSeeder::class,
        ]);
    }
}
