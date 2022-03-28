<?php

namespace App\Extensions\Expressbird\Controllers;

use Gtd\Suda\Http\Controllers\SiteController as SiteCtl;

class SiteController extends SiteCtl
{
    public $extension_view = 'expressbird';    //定义view目录
    
    public function index(){
        
        return $this->display('index');
    }
    
    public function responseJson($data,$code=200){
        
        return Response::json($data, $code);
        
    }
}
