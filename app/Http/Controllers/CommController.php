<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class CommController extends Controller
{
    public function index(Request $request){
        $key = $request->input('key');

        if($key != "appSetup_key"){
            try {
                switch($key){
                    case 'migrate':
                        Artisan::call('migrate');
                        break;
                    case 'seed':
                        Artisan::call('db:seed');
                        break;
                    case 'clear':
                        Artisan::call('config:cache');
                        Artisan::call('route:clear');
                        break;
                    case 'clear_config':
                        Artisan::call('config:clear');
                        Artisan::call('cache:clear');
                        break;
                    case 'setup':
//                     Artisan::call('key:generate');
                        break;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }else{
            echo "Access Denied";
        }
    }
}
