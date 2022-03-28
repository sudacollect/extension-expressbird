<?php

namespace App\Extensions\Expressbird\Controllers;

use Gtd\Suda\Http\Controllers\MobileController as MobCtl;

class MobileController extends MobCtl
{
    
    public $extension_view = 'expressbird';    //定义view目录
    
    public function index(){
        
        exit('这是移动首页');
    }
    
    public function responseJson($data,$code=200){
        
        return Response::json($data, $code);
        
    }
}
