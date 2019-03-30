<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LinhaTeoricaSeeder::class,
            PerfisSeeder::class,
            UsersSeeder::class,
            RoleSeeder::class,
            PermissionsSeeder::class,
            TesteSeeder::class
        ]);
    }
}
