##框架用法

###1.生成基础数据表
``` 
php artisan migrate
```

###2.安装passport
```
php artisan passport:install
```

###3.设置中间件

App\Http\Kernel.php中加入middleware加入代码

    protected $routeMiddleware = [
        ...
        'authenticate'  => \Core\Components\Middleware\Authenticate::class,
        'transaction'   => \Core\Components\Middleware\Transaction::class,
    ];
    
描述：

authenticate : 检测登录与权限

transaction : 事务中间件

###4.修改Exception Handler
 修改为继承Exception
 
        ...
        class Handler extends Core\Components\Base\Handler {
        ...
        
###5.修改config/auth.php
 修改为passport配置
 
        ...
        'guards' => [
                'admin-api' => [
                    'driver' => 'passport',
                    'provider' => 'admin-api',
                ],
                ...
        ],
        
        'providers' => [
                'admin-api' => [
                    'driver' => 'eloquent',
                    'model' => \Core\Components\Models\AdminUser::class,
                ],
                ...
        ],
        ...
        
        
        
####备注：

composer拓展

    ...
    "laravel/passport",
    "phpoffice/phpspreadsheet",
    "spatie/laravel-permission",
    "tucker-eric/eloquentfilter"