<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use \Carbon\Carbon;

class AddRolesSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon::now();
        Role::insert(
            array(
                array('role'=>"Super Admin","created_at"=>$time),
                array('role'=>"Customer","created_at"=>$time),
                array('role'=>"Admin","created_at"=>$time),
            )
        );
    }
}
