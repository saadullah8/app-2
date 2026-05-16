<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddSuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=new User();
        $user->first_name = "Super";
        $user->last_name ="Admin";
        $user->email ="info@gmail.com";
        $user->password =  bcrypt('admin');
        $user->role_id=1;
        $user->status=1;
        $user->save();
    }
}
