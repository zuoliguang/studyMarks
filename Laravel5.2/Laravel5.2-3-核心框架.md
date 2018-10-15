## 3、核心框架

### 3.1 入口

***开始***

`public/index.php` 文件是所有对 `Laravel` 应用程序的请求的入口点。

而所有的请求都是经由你的 Web 服务器（Apache/Nginx）通过配置引导到这个文件。

index.php 文件加载 Composer 生成定义的自动加载器，然后从 bootstrap/app.php 脚本中检索 Laravel 应用程序的实例。

Laravel 本身采取的第一个动作是创建一个 application/ service container 的实例。

***HTTP / 控制内核***

HTTP 内核继承了 Illuminate\Foundation\Http\Kernel 类，它定义了在执行请求之前运行的 bootstrappers 数组。

这个数组负责在实际处理请求之前完成这些内容：配置错误处理、配置日志记录、 检测应用环境 以及执行其他需要完成的任务。

HTTP 内核还定义了所有请求被应用程序处理之前必须经过的 HTTP 中间件 的列表。

这些中间件处理 HTTP 会话 的读写、确定应用程序是否处于维护模式、 验证 CSRF 令牌 等。

HTTP 内核的 handle 方法的方法签名非常简单：接收 Request 并返回 Response 。

可以把内核当作是代表整个应用程序的大黑盒，给它 HTTP 请求，它就返回 HTTP 响应。

***服务提供器***

最重要的内核引导操作之一是加载应用程序的 服务提供器 。应用程序的所有服务提供器都在 config/app.php 配置文件的 providers 数组中配置。

首先，所有提供器都会调用 register 方法，接着，由 boot 方法负责调用所有被注册提供器。

***分配请求***

一旦引导了应用程序且注册所有服务提供器，Request 请求就会被转交给路由器来进行调度。路由器将请求发送到路由或控制器或任何运行于路由的特定中间件。

***聚焦服务提供器***

服务提供器是引导 Laravel 应用程序真正的关键。创建应用程序实例、注册服务提供器，并将请求交给被引导的应用程序。

### 3.2 中间件 Facades

Facades（读音：/fəˈsäd/ ）为应用程序的 服务容器 中可用的类提供了一个「静态」接口。

Laravel 自带了很多 Facades ，可以访问绝大部分 Laravel 的功能。

Laravel Facades 实际上是服务容器中底层类的「静态代理」，它提供了简洁而富有表现力的语法，甚至比传统的静态方法更具可测试性和扩展性。

所有的 Laravel Facades 都在 `Illuminate\Support\Facades` 命名空间中定义。

```php
use Illuminate\Support\Facades\Cache;

Route::get('/cache', function () {
    return Cache::get('key');
});
```

***Facades Vs. 辅助函数***

除了 Facades， Laravel 还包含各种 『辅助函数』来实现一些常用功能，比如生成视图、触发事件、任务调度或者发送 HTTP 响应。 

```php
//return View::make('profile');
return view('profile');

//return Cache::get('key');
return cache('key');

```

***Facades 工作原理***

在 Laravel 应用中， Facade 就是一个可以从容器访问对象的类。其中核心的部件就是 Facade 类。 

不管是 Laravel 自带的 Facades ， 还是自定义的 Facades ，都继承自 Illuminate\Support\Facades\Facade 类。


Facade 类参考

| Facade	                | 类	                                               | 服务容器绑定           |
| :------------------------ | :-----------------------------------------------: | --------------------: |
| App	                    | Illuminate\Foundation\Application	                | app                   |
| Artisan	                | Illuminate\Contracts\Console\Kernel	            | artisan               |
| Auth	                    | Illuminate\Auth\AuthManager	auth                |                       |
| Auth (Instance)	        | Illuminate\Contracts\Auth\Guard	                | auth.driver           |
| Blade	                    | Illuminate\View\Compilers\BladeCompiler	        | blade.compiler        |
| Broadcast	                | Illuminate\Contracts\Broadcasting\Factory	        |                       |
| Broadcast (Instance)	    | Illuminate\Contracts\Broadcasting\Broadcaster     | 	                    |
| Bus	                    | Illuminate\Contracts\Bus\Dispatcher	            |                       |
| Cache	                    | Illuminate\Cache\CacheManager	                    | cache                 |
| Cache (Instance)	        | Illuminate\Cache\Repository	                    | cache.store           |
| Config	                | Illuminate\Config\Repository	                    | config                |
| Cookie	                | Illuminate\Cookie\CookieJar	                    | cookie                |
| Crypt	                    | Illuminate\Encryption\Encrypter	                | encrypter             |
| DB	                    | Illuminate\Database\DatabaseManager	            | db                    |
| DB (Instance)	            | Illuminate\Database\Connection	                | db.connection         |
| Event	                    | Illuminate\Events\Dispatcher	                    | events                |
| File	                    | Illuminate\Filesystem\Filesystem	                | files                 |
| Gate	                    | Illuminate\Contracts\Auth\Access\Gate	            |                       |
| Hash	                    | Illuminate\Contracts\Hashing\Hasher	            | hash                  |
| Lang	                    | Illuminate\Translation\Translator	                | translator            |
| Log	                    | Illuminate\Log\Logger	                            | log                   |
| Mail	                    | Illuminate\Mail\Mailer	                        | mailer                |
| Notification	            | Illuminate\Notifications\ChannelManager	        |                       |
| Password	                | Illuminate\Auth\Passwords\PasswordBrokerManager	| auth.password         |    
| Password (Instance)	    | Illuminate\Auth\Passwords\PasswordBroker	        | auth.password.broker  |
| Queue	                    | Illuminate\Queue\QueueManager	                    | queue                 |
| Queue (Instance)	        | Illuminate\Contracts\Queue\Queue	                | queue.connection      |    
| Queue (Base Class)	    | Illuminate\Queue\Queue	                        |                       |    
| Redirect	                | Illuminate\Routing\Redirector	                    | redirect              |
| Redis	                    | Illuminate\Redis\RedisManager	                    | redis                 |
| Redis (Instance)	        | Illuminate\Redis\Connections\Connection	        | redis.connection      |    
| Request	                | Illuminate\Http\Request	                        | request               |
| Response	                | Illuminate\Contracts\Routing\ResponseFactory	    |                       |    
| Response (Instance)	    | Illuminate\Http\Response	                        |                       |
| Route	                    | Illuminate\Routing\Router	                        | router                |
| Schema	                | Illuminate\Database\Schema\Builder	            |                       |
| Session	                | Illuminate\Session\SessionManager	                | session               |
| Session (Instance)	    | Illuminate\Session\Store	                        | session.store         |
| Storage	                | Illuminate\Filesystem\FilesystemManager	        | filesystem            |
| Storage (Instance)	    | Illuminate\Contracts\Filesystem\Filesystem	    | filesystem.disk       |
| URL	                    | Illuminate\Routing\UrlGenerator	                | url                   |
| Validator	                | Illuminate\Validation\Factory	                    | validator             |
| Validator (Instance)	    | Illuminate\Validation\Validator	                |                       |
| View	                    | Illuminate\View\Factory	                        | view                  |
| View (Instance)	        | Illuminate\View\View	                            |                       |



### 3.3 容器

点击这里可以查看 [官方文档](https://laravel-china.org/docs/laravel/5.6/container)

