<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AddProductType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = \Carbon\Carbon::now();
        Category::insert(
            array(
                array('name'=>"Fixed Product","created_at"=>$time),
                array('name'=>"Customized Product","created_at"=>$time),
                array('name'=>"Meal Product","created_at"=>$time),
            )
        );
    }
}
