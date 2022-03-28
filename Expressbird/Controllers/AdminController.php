<?php

namespace App\Extensions\Expressbird\Controllers;

use Gtd\Suda\Http\Controllers\Admin\ExtensionController;

use Illuminate\Support\Facades\Gate;

class AdminController extends ExtensionController{
    
    public $single_extension_menu = true;
    
    public function __construct(){

        parent::__construct();


        // //增加policy映射关系
        // Gate::policy(\Gtd\Suda\Models\Setting::class, \App\Extensions\Expressbird\Policies\DemoPolicy::class);
        
        // //定义权限
        // Gate::define('demo.view', 'App\Extensions\Expressbird\Policies\DemoPolicy@view');
    }
}

