#重点说明

> 所有快递配送都要基于expressbird去实现

配置 config/expressbird.php

配置需要使用的配送方式和默认配送方式

app/Providers/AppServiceProvider.php

register 新增

```
use Illuminate\Routing\Router;
use App\Extensions\Expressbird\Contracts\ExpressbirdFactory;
use App\Extensions\Expressbird\Services\ExpressbirdManager;
use App\Extensions\Expressbird\Middleware\ExpressbirdMiddleware;


public function register()
{

    $this->registerExpress();
    $this->registerExpressResolver();
}

protected function registerExpress()
{
    $this->app->singleton('expressbird', function ($app) {
        return new ExpressbirdManager($app);
    });

}

/**
    * Register a resolver for the expressbird.
    *
    * @return void
    */
protected function registerExpressResolver()
{
    $this->app->bind(
        ExpressbirdFactory::class, function ($app) {
            return call_user_func($app['expressbird']->resolver());
        }
    );
}

```

boot 新增一行

```
public function boot()
{
    $router->middlewareGroup('expressbird', [ExpressbirdMiddleware::class]);
}

```

使用方法，在middleware内可以直接使用 ExpressFactory 自动识别对应的配送代码

普通使用 

```
// 例如美团
app('expressbord')->channel('meituan');//对应driver

```
