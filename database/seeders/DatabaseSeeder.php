<?php
namespace Database\Seeders;

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
        $this->call(AddRolesSeeder::class);
        $this->call(AddSuperAdmin::class);
        $this->call(AddProductType::class);
        $this->call(AddDefaultMessage::class);
    }
}
