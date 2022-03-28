<?php

namespace App\Extensions\Expressbird\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Response;


class ApiController extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    
    
    
    //自定义函数
    
    public function responseJson($data,$code=200){
        
        return Response::json($data, $code);
        
    }
}