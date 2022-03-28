<?php

namespace App\Extensions\Expressbird\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Extensions\Expressbird\Services\ExpressbirdManager;
use Session;

class ExpressbirdMiddleware
{
    public $driver;
    
    public function handle(Request $request, Closure $next) {
        
        $routes = Route::currentRouteName();

        $routes = explode('.',$routes);
        

        $drivers = config('expressbird.drivers',[]);

        $driver = '';
        

        // 根据系统设置，key=3的路由参数就是当前配送公司的编码
        if(count($routes)>=4 && array_key_exists($routes[3],$drivers))
        {
            $driver = $routes[3];
        }
        
        if($driver){
            
            app('expressbird')->shouldUse($driver);
            

            return $next($request);
        }
        
        return $next($request);
        
    }
    
}
