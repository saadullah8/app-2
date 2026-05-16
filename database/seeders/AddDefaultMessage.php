<?php
namespace Database\Seeders;
use App\Models\DefaultMessage;
use Illuminate\Database\Seeder;

class AddDefaultMessage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = \Carbon\Carbon::now();
        DefaultMessage::insert(
            array(
                array('message'=>"hi {{name}} , your order No. {{orderNo}} has been received",'type'=>'new_order_tab',"created_at"=>$time),
                array('message'=>"hi {{name}} , your order No. {{orderNo}} has been ready to pick up",'type'=>'button_ready_order',"created_at"=>$time),
                array('message'=>"hi {{name}} , your order No. {{orderNo}} has been cancel",'type'=>'button_cancel_order',"created_at"=>$time),
                array('message'=>"hi {{name}} , your order No. {{orderNo}} has been ready to pick up",'type'=>'pick_up_order_tab',"created_at"=>$time),
                array('message'=>"hi {{name}} , your order No. {{orderNo}} has been cancel",'type'=>'cancel_order_tab',"created_at"=>$time),
            )
        );
    }
}
