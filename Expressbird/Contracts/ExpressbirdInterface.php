<?php

namespace App\Extensions\Expressbird\Contracts;

interface ExpressbirdInterface
{
    

    public function channel($channel_name);

    public function resolve($name);

    public function shouldUse($name);

}