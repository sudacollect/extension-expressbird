<?php

namespace App\Extensions\Expressbird\Services;

use InvalidArgumentException;
use App\Extensions\Expressbird\Contracts\ExpressbirdInterface;

class ExpressbirdManager implements ExpressbirdInterface
{
    protected $app;
    protected $channel_name;
    protected $resolver;

    public function __construct($app)
    {
        $this->app = $app;

        // 定义默认的channel, 才能初始化成功
        // 
        $this->resolver = function ($name = null) {
            return $this->channel($name);
        };
    }

    public function channel($name = '')
    {

        $name = $name ?: $this->getDefaultDriver();
        if(!$name)
        {
            throw new InvalidArgumentException("Express driver is empty.");
        }
        $this->channel_name = $name;
        return $this->resolve($this->channel_name);
    }

    public function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Express driver [{$name}] is not defined.");
        }
        
        return $this->callDriver($name, $config);

        throw new InvalidArgumentException(
            "Express driver [{$name}] is not defined."
        );
    }

    public function resolver()
    {
        return $this->resolver;
    }


    public function getDefaultDriver()
    {
        return $this->app['config']['expressbird.default'];
    }

    public function setDefaultDriver($name)
    {
        $this->app['config']['expressbird.default'] = $name;
    }

    public function shouldUse($name)
    {
        $name = $name ?: $this->getDefaultDriver();

        $this->setDefaultDriver($name);
        
        $this->resolver = function ($name = null) {
            return $this->channel($name);
        };
    }

    protected function getConfig($name)
    {
        return $this->app['config']["expressbird.drivers.{$name}"];
    }

    protected function callDriver($name, array $config)
    {
        return new $config['driver']($name, $config);
    }
    
}