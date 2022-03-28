<?php

namespace App\Extensions\Expressbird\Policies;


use Gtd\Suda\Policies\BasePolicy;

class DemoPolicy extends BasePolicy
{
    
    protected function view()
    {
        //可增加自定义的方法
        return parent::checkPermission();
    }
    
    
}
