##框架用法

###1.生成基础数据表
``` 
php artisan migrate
php artisan migrate --path=/Core/Databases/migrations 
```

###2.安装passport
```
php artisan passport:install
```

###3.设置路由
App\Providers\RouteServiceProvider.php中加入代码
```  Core::routers(); ```

App\Http\Kernel.php中加入middleware加入代码

    protected $routeMiddleware = [
        ...
        'authenticate'  => \Core\Components\Middlewares\Authenticate::class,
        'transaction'   => \Core\Components\Middlewares\Transaction::class,
    ];
    
描述：

authenticate : 检测登录与权限

transaction : 事务中间件

###4.修改Exception Handler
 修改为继承Exception
 
        ...
        class Handler extends Core\Components\Base\Handler {
        ...
        
####备注：

composer拓展

    ...
    "laravel/passport",
    "phpoffice/phpspreadsheet",
    "spatie/laravel-permission",
    "tucker-eric/eloquentfilter"