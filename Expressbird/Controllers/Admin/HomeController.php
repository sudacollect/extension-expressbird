<?php

 
namespace App\Extensions\Expressbird\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

use App\Extensions\Expressbird\Controllers\AdminController;
use Gtd\Suda\Models\Setting;

use GuzzleHttp\Client as HttpClient;

class HomeController extends AdminController{
    
    public $self_url = 'extension/expressbird/setting';
    
    public function index(Request $request)
    {
        $this->gate('basic_menu.printer_status',app(Setting::class));
        
        $this->title('首页');


        // $this->setData('data',$data);
        
        $this->setMenu('basic_menu','printer_status');
        return $this->display('index');
    }
    
    
    
}

