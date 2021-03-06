## 2、HelloWorld 测试学习

### 2.1 路由
直接将结果写在 `Project/app/Http/routes.php` 中
```php
Route::get('/study/route', function () {
	return 'Hello World, zuoliguang!';
});
```
或者返回到对应的模板中展示
```php
Route::get('/', function () {
	return view('welcome', ['title'=>'zuoliguang']);
});
```
或者指定到固定的控制器来处理,将 `http:://ip/study` 指向 `Project/app/Http/Controllers/Study/IndexController.php` 下的 `index` 方法
```php
Route::get('/study', 'Study\IndexController@index');
```
控制器路由命名及使用, `Project/app/Http/routes.php`中代码
```php
Route::get('/study', 'Study\IndexController@index')->name('index');
```
在 `Project/app/Http/Controllers/Study/IndexController.php` 中调用方式（跳转使用）
```php
return redirect()->route('index');
```
参数的传递方式及参数约束 在路由中可写  其中 `name` 可为空
```php
Route::get('/study/username/{id}/{name?}', 'Study\IndexController@username')->where(['id' => '[0-9]+','name' => '[A-Za-z]+']);
```
控制器中接收参数的方式
```php
public function username($id, $name='zlgcg'){
	var_dump($id);
	var_dump($name);
}
```

资源路由器（将剩余未定义的方法按照方法名定义）
```php
Route::resource('study/index', 'Study\IndexController');
// 或者
Route::resources([
	'photos' => 'PhotoController',
	'posts' => 'PostController'
]);
```
声明资源路由时，你可以指定控制器应该处理的部分行为，而不是所有默认的行为
```php
Route::resource('photos', 'PhotoController', ['only' => [
    'index', 'show'
]]);

Route::resource('photos', 'PhotoController', ['except' => [
    'create', 'store', 'update', 'destroy'
]]);
```
### 2.2 middleware 中间件
middleware 中间件可在路由中接收是对参数进行约束校验，我们可以使用框架工具直接生成一个 CheckTest 中间件，并使用其对id的限制

执行 `php artisan make:middleware CheckTest`

会生成 `Project/app/Http/Middleware/CheckTest.php` 修改代码
```php
namespace App\Http\Middleware;
use Closure;
class CheckTest
{
	/**
	* 自定义测试中间件
	* 检查该条件参数id
	* id 小于等于100 正常；否则，跳转到 there 路由去；
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next)
	{
		if ($request->id > 100) {
			return redirect()->route('there');
		}
		return $next($request);
	}
}
```
注册该自定义中间件使其生效，给该自定义插件赋值 `checkId` 修改代码 `Project/app/Http/Kernel.php` 中 `$routeMiddleware` 变量添加
```php
protected $routeMiddleware = [
	'checkId' => \App\Http\Middleware\CheckTest::class, // 该位置注册自定义的中间件 CheckTest
];
```
使用时 在路由文件中对参数约束方式
```php
// 这里的 checkId 对应的是 Kernel.php 文件里变量 $routeMiddleware 下的 key 值
Route::get('/study/user/{id}', 'Study\IndexController@user')->where(['id' => '[0-9]+'])->middleware('checkId');
```

### 2.3 CSRF 保护、表单验证
在对应的`form`表单中添加 `{{ csrf_field() }}` 代码会自动在表单中添加隐含字段
```html
<input type="hidden" name="_token" value="Q55j85A8UPfYJ4xKkInEx6tVapk2r4zRacxhv0n0">
```
可在提交的控制器中验证 `_token` 值 和 `csrf_token()` 的值
```php
$_token = $request->_token;
if($_token==csrf_token()){
	// 处理数据
} else {
	// 否则不处理、报错
}
```
### 2.4 Request 请求
在上传信息的接口位置可以使用 `use Illuminate\Http\Request` 创建接受类，控制器中代码
```php
public function postdata(Request $request) {
	$uri = $request->path(); // 获取实际路径
	$url = $request->url(); // 获取地址
	$fullUrl = $request->fullUrl(); // 获取地址
	$is_post = $request->isMethod('post'); // 是否是post
	$is_get = $request->isMethod('get'); // 是否是get
	$test = $request->test;
	$all = $request->all(); // 获取传入的所有参数
	$isHas = $request->has(['name', 'email']); // 是否有 name、email 字段信息

	echo "<pre>";
	var_dump($uri);
	var_dump($url);
	var_dump($fullUrl);
	var_dump($is_post);
	var_dump($is_get);
	var_dump($test);
	var_dump($all);
	var_dump($isHas);
}
```
或者直接打印查看 `$request` 内容

**使用Route** 

如果要获取的是路径信息 可以使用 `use Illuminate\Support\Facades\Route` 来直接获得
```php
$route = Route::current();
$name = Route::currentRouteName();
$action = Route::currentRouteAction();
```
### 2.5 Responses 响应、重定向

路由控制器中可直接采用return响应
```php
Route::get('/', function () {
    return 'Hello World';
});
// 除了从路由和控制器返回字符串之外，还可以返回数组。框架会自动将数组转为 JSON 响应。
Route::get('/', function () {
    return [1, 2, 3];
});
```
你还可以从路由或控制器中直接返回 Eloquent ORM 集合, 它们会自动转为 JSON 响应.

***自定义 响应对象***


控制器中可以使用 `Illuminate\Http\Response` 来创建响应信息.

路由中也可以直接将结果返回
```php
Route::get('home', function () {
    return response('Hello World', 200)->header('Content-Type', 'text/plain');
});
```
该响应类 Response 实例继承自 Symfony\Component\HttpFoundation\Response 类

***为响应增加头信息***

```php
return response($content)
	->header('Content-Type', $type)
	->header('X-Header-One', 'Header Value')
	->header('X-Header-Two', 'Header Value');
```
或者
```php
return response($content)
	->withHeaders([
		'Content-Type' => $type,
		'X-Header-One' => 'Header Value',
		'X-Header-Two' => 'Header Value',
	]);
```
***为响应增加 Cookie***

```php
return response($content)
	->header('Content-Type', $type)
	->cookie('name', 'value', $minutes);
```
***城定向***

重定向至命名路由, 当你不带参数调用辅助函数 redirect 时，会返回 Illuminate\Routing\Redirector 实例。这个实例允许你调用 Redirector 上的任何方法。
```php
return redirect()->route('index');
```
带参数时
```php
// 对于具有以下 URI 的路由: profile/{id}
return redirect()->route('profile', ['id' => 1]);
```
重定向至控制器行为
```php
return redirect()->action('HomeController@index');
// 带参数
return redirect()->action(
    'UserController@profile', ['id' => 1]
);
return redirect()->action('Study\IndexController@username', ['name'=>'testName']);
```
重定向到外部域
```php
return redirect()->away('https://www.google.com');
```
**其他响应类型**

***视图响应***

```php
return response()
	->view('hello', $data, 200)
	->header('Content-Type', $type);
```

***JSON 响应***

```php
return response()->json([
	'name' => 'Abigail',
	'state' => 'CA'
]);
```
JSONP 响应，你可以使用 json 方法并与 withCallback 方法配合使用
```php
return response()
	->json(['name' => 'Abigail', 'state' => 'CA'])
	->withCallback($request->input('callback'));
```

***文件下载***

注:管理文件下载的扩展包 Symfony HttpFoundation，要求下载文件名必须是 ASCII 编码的
```php
return response()->download($pathToFile); // 下载指定的文件名称
return response()->download($pathToFile, $name, $headers); // 以指定的名称显示到用户眼前
```

***文件响应***

file 方法可以直接在用户浏览器中显示文件（不是发起下载），例如图像或者 PDF。
```php
return response()->file($pathToFile);
return response()->file($pathToFile, $headers);
```

### 2.6 <a name="uploadFile">文件上传</a>

文件上传操作类 `use Illuminate\Support\Facades\Storage` 和 `use Illuminate\Http\Request` 

***上传地址配置***

配置文件地址 `Project/config/filesystems.php` 
```php
'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('upload'), // 该位置设置文件保存地址
        ]
    ]
```
上传页面
```php
<form method="POST" action="/study/doupload" enctype="multipart/form-data">
	{{ csrf_field() }}
	<input type="file" name="file">
	<button type="submit">submit</button>
</form>
```
后台文件接收
```php
// 文件上传, 将获得的有效文件使用插件保存到指定地址即可 
public function doupload(Request $request)
{
	$file = $request->file('file');
	$file_path = ''; // 上传后的文件地址 
	// 验证文件是否有效
	if ($file->isValid()) {
		$path = $file->path(); // 临时文件的存放地址
		$name = $file->getClientOriginalName(); // 图片名称
		$ext = $file->getClientOriginalExtension(); // 图片格式
		$size = $file->getClientSize(); // 图片大小
		$file_content = file_get_contents($path);
		$file_name = date('Y-m-d-H-i-s-').$name;
		Storage::disk('local')->put($file_name, $file_content);
		$file_path = config()->get('filesystems.disks.local.root')."/".$file_name;
	}
	
	if (!empty($file_path)) {
		echo '文件上传成功，地址：'.$file_path;
	} else {
		echo '文件上传失败';
	}
}
```

### 2.7 URL 处理

***生成基础 URL***
```php
$post = App\Post::find(1);
echo url("/posts/{$post->id}");
// http://example.com/posts/1
```

***访问当前 URL***

如果没有给辅助函数 url 提供路径，则会返回一个 Illuminate\Routing\UrlGenerator 实例，来允许你访问有关当前 URL 的信息
```php
// 获取没有查询字符串的当前的 URL ...
echo url()->current();

// 获取包含查询字符串的当前的 URL ...
echo url()->full();

// 获取上一个请求的完整的 URL...
echo url()->previous();
```

上面的这些方法都可以通过 URL facade 访问
```php
use Illuminate\Support\Facades\URL;
echo URL::current();
```

[More关于URL](https://laravel-china.org/docs/laravel/5.6/urls)

### 2.8 错误处理
config/app.php 配置文件中的 debug 选项决定了对于一个错误实际上将显示多少信息给用户。

本地开发时，`APP_DEBUG=true`;

生产环境时，`APP_DEBUG=false`;

所有异常都是由 `App\Exceptions\Handler` 类处理的。这个类包含两个方法：`report` 和 `render`。

***异常处理器 Report 方法***

```php
namespace App\Exceptions;
public function isValid($value)
{
    try {
        // Validate the value...
    } catch (Exception $e) {
        report($e);
        return false;
    }
}
```

***HTTP 异常***

一些异常用于描述产生自服务器的 HTTP 错误代码。

使用 `abort` 辅助函数可以从应用程序的任何地方生成这样的响应。

```php
abort(404);
```

***自定义 HTTP 错误页面***

`Laravel` 可以轻松显示各种 HTTP 状态代码的自定义错误页面。

如果你希望自定义 404 HTTP 状态码的错误页面，可以创建一个 `Project/resources/views/errors/404.blade.php` 视图文件。

该文件将被用于你的应用程序产生的所有 404 错误。

此目录中的视图文件的命名应匹配它们对应的 HTTP 状态码。由 abort 函数引发的 HttpException 实例将作为 $exception 变量传递给视图.

```php
<h2>{{ $exception->getMessage() }}</h2>
```

### 2.9 模板（数据模型）
Laravel 的 Eloquent ORM 提供了漂亮、简洁的 ActiveRecord 实现来和数据库交互。
每个数据库表都有一个对应的「模型」用来与该表交互。你可以通过模型查询数据表中的数据，并将新记录添加到数据表中。

***定义模型***

创建模型实例的最简单方法是使用 Artisan 命令 make:model： 
`php artisan make:model User`

如果要在生成模型时生成 数据库迁移 ，可以使用 --migration 或 -m 选项：
`php artisan make:model User --migration` 
or 
`php artisan make:model User -m`

***Eloquent 模型约定***

```php
namespace App;
use App\Pwd;
use App\Say;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * 与模型关联的数据表
     * @var string
     */
    protected $table = 'member';

    /**
     * 模型的主键
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * 该模型是否被自动维护时间戳
     * @var bool
     */
    public $timestamps = false;

	/**
     * 模型的日期字段的存储格式
     *
     * @var string
     */
    // protected $dateFormat = 'U';

	/**
     * 数据模型专属的数据库连接
     *
     * @var string
     */
    // protected $connection = 'connection-name';

    /**
     * 添加可以批量赋值的字段
     * @var array
     */
    protected $fillable = ['name', 'age', 'tel', 'address', 'score', 'class', 'ext_info'];

    /**
	 * 不可被批量赋值的属性
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * 关联 pwd 表 一对一
	 */
	public function pwd()
	{
		return $this->hasOne('App\Pwd', 'm_id', 'id'); // 关联的模型，关联模型对应的字段，当前模型的字段
	}

	/**
	 * 关联 say 表 一对多
	 */
	public function say()
	{
		return $this->hasMany('App\Say', 'm_id', 'id');
	}
}
```

> * 主键 

Eloquent 也会假定每个数据表都有一个名为 id 的主键字段。你可以定义一个访问权限为protected的 $primaryKey 属性来覆盖这个约定。

> * 时间戳

默认情况下，Eloquent 会默认数据表中存在 created_at 和 updated_at 这两个字段。
如果你不需要这两个字段，则需要在模型内将 $timestamps 属性设置为 false。
如果你需要自定义时间戳格式，可在模型内设置 $dateFormat 属性。

> * 数据库连接

默认情况下，所有的模型使用应用配置中的默认数据库连接。如果你想要为模型指定不同的连接，可以通过 $connection 属性来设置。
配置文件 `Project/config/database.php` 下 `connections` 对应的值下面的数据库。

***获取模型，控制器种使用方式***

```php
use App\Member;

// 1、获取全部数据
$members = Member::all(); 

// 2、获取条件搜索数据
$members = Member::where('id', '<', 10)->orderBy('id', 'desc')->get(); 

// 3、分块结果
Member::chunk(2, function($members){
	foreach ($members as $member) {
		echo $member->name;
		echo '<br/>';
	}
	echo '------------------<br/>';
});

// 4、取回单个信息
$member = Member::find(1);
$member = Member::where('id', '>', 10)->first();
var_dump($member);

// 5、取回指定数据集
$members = Member::find([12, 13, 14]);
foreach ($members as $member) {
	echo $member->name;
	echo "<br/>";
}

// 6、添加
$member = new Member;
$member->name 		= 'model';
$member->age 		= 40;
$member->tel 		= '88888888';
$member->address 	= 'peking zhanghao dizhi hahah';
$member->score 		= 99;
$member->class 		= '2-9';
$member->ext_info 	= 'this is a model test model info';
$member->save();

// 7、更新
$member = Member::find(14);
$member->name = 'test_mode';
$member->save();

// 8、批量更新
Member::where('id', 8)->update(['name'=>'test_update']);
Member::where('id', '>', 8)->where('id', '<=', 13)->update(['name'=>'test_update_agin']);

// 9、删除
$member = Member::find(7);
$member->delete();

// 10、批量删除
Member::destroy(1);
Member::destroy([1, 2, 3]);

// 11、获取关联信息
$member = Member::find(2);

// 12、获取一对一的数据
$pwd = $member->pwd; // 一对一
var_dump($pwd->pwd);

// 获取一对多的数据
$says = $member->say; // 一对多
foreach ($says as $say) {
	var_dump($say->say);
	echo '<br/>';
}
```

***集合***

使用 Eloquent 中的 all 和 get 方法可以检索多个结果，并会返回一个 Illuminate\Database\Eloquent\Collection 实例。
Collection 类提供了 很多辅助函数 来处理 Eloquent 结果。

```php

// 取出对象的name信息
$members = $members->reject(function ($member) {
    return $member->name;
});

//分块结果 ,数据分2个一组，然后按组来循环处理数据
Member::chunk(2, function($members){
	foreach ($members as $member) {
		echo $member->name;
		echo '<br/>';
	}
	echo '------------------<br/>';
});

// 使用游标,该游标只执行一个查询。处理大量数据时，使用 cursor 方法可以大幅度减少内存的使用量
foreach (Member::where('name', 'zlgcg')->cursor() as $member) {
    //处理数据
}

// 找不到数据信息时抛出异常
// 如果你希望在找不到模型时抛出异常，可以使用 findOrFail 以及 firstOrFail 方法。
// 这些方法会检索查询的第一个结果，如果没有找到相应的结果，就会抛出一个 Illuminate\Database\Eloquent\ModelNotFoundException 错误。
$model = App\Flight::findOrFail(1);
$model = App\Flight::where('legs', '>', 100)->firstOrFail();

// 如果没有对异常进行捕获，则会自动返回 404 响应给用户。也就是说，在使用这些方法时，不需要另外写个检查来返回 404 响应
Route::get('/api/flights/{id}', function ($id) {
    return App\Flight::findOrFail($id);
});

// 检索集合
// 还可以使用 查询构造器 提供的 count 、 sum 、 max 以及其它 聚合函数 。这些方法只会返回适当的标量值而不是整个模型实例
$count = App\Flight::where('active', 1)->count();
$max = App\Flight::where('active', 1)->max('price');

```

**注：增删改查的操作可参考上面 Member 类的操作。**

这里补充一个批量新增的操作:

***批量新增***

该操作需要在模型中设置可以被批量赋值的属性，还可以是指不可被赋值的属性。

```php
/**
 * 可以被批量赋值的属性。
 *
 * @var array
 */
protected $fillable = ['name','age'];

/**
 * 不可被批量赋值的属性。(该设置可用来保护属性)
 *
 * @var array
 */
protected $guarded = ['price'];
```
控制器里操作, 当我们设置好批量赋值的属性，就可以通过 create 方法插入新数据。返回值为被创建的实例对象。
```php
$flight = App\Flight::create(['name' => 'Flight 10']);
```

***其它创建方法***

```php

// 通过 name 查找航班，不存在则创建...
$flight = App\Flight::firstOrCreate(['name' => 'Flight 10']);

// 通过 name 查找航班，不存在则使用 name 和 delayed 属性创建...
$flight = App\Flight::firstOrCreate(
    ['name' => 'Flight 10'], ['delayed' => 1]
);

// 通过 name 查找航班，不存在则创建一个实例...
$flight = App\Flight::firstOrNew(['name' => 'Flight 10']);

// 通过 name 查找航班，不存在则使用 name 和 delayed 属性创建一个实例...
$flight = App\Flight::firstOrNew(
    ['name' => 'Flight 10'], ['delayed' => 1]
);

// 如果有从奥克兰到圣地亚哥的航班，则价格定为99美元。
// 如果没匹配到存在的模型，则创建一个。
$flight = App\Flight::updateOrCreate(
    ['departure' => 'Oakland', 'destination' => 'San Diego'],
    ['price' => 99]
);

```

> * 软删除
> * 查询软删除模型
> * 检索软删除模型
> * 恢复软删除模型
> * 永久删除
> * 查询作用域

已上从操作可查看[Eloquent ORM 其他可查看文档地址](https://laravel-china.org/docs/laravel/5.6/eloquent#ad4448)

***事件***

> Eloquent 模型会触发许多事件，让你在模型的生命周期的多个时间点进行监控： retrieved, creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored。 事件让你在每当有特定模型类进行数据库保存或更新时，执行代码。

> 当从数据库检索现有模型时，会触发retrieved 事件。

> 当模型第一次被保存时， creating 和 created 事件会被触发。

> 若数据库中已存在此模型，并调用 save 方法，updating / updated 事件会被触发。 这两种情况下，saving / saved 事件都会被触发。

> 当删除数据是触发 deleting, deleted 事件。

> 当恢复软删除时操作 restoring, restored 事件。

###### 已上操作均可使用 [观察者](https://laravel-china.org/docs/laravel/5.6/eloquent#cab083) 类来监听操作


### 2.10 视图 及 前端资源

具体可以查看[Blade模板文档](https://github.com/zuoliguang/studyMarks/blob/master/Blade%E6%A8%A1%E6%9D%BF.md)
或者查看官方[模板文档](https://laravel-china.org/docs/laravel/5.6/blade)

***前端资源引入***

这里我们把前端资源的文件都放在了 `Project/public` 下, 和入口文件一样的位置，所以可以直接在模板文件里写
```php
<link href="/css/test.css" rel="stylesheet" type="text/css">
```
本地化的javascript 及 图片资源均可以同样方式使用。

> 注：客户上传图片及文件最好使用第三方的服务平台进行存储，以免造成服务器宕机。

### 2.11 日志

Laravel 的日志系统是基于 `Monolog` 库，`Monolog` 提供了多种强力的日志处理程序。

#### 配置

Laravel 5.2 框架里直接饮用的 `use Illuminate\Support\Facades\Log` ;

在代码中使用的方式是
```php
$message = 'this is an log infomation for laravel study!!!!!!';
Log::emergency($message.'-emergency ');
Log::alert($message.'-alert ');
Log::critical($message.'-critical ');
Log::error($message.'-error ');
Log::warning($message.'-warning ');
Log::notice($message.'-notice ');
Log::info($message.'-info ');
Log::debug($message.'-debug ');
```
> 日志文件默认记录的地址 `APP_PATH/storage/logs/laravel.log`, 日志的信息格式可以自己定义, 实际开发中会将该文件保存到根路径下或者项目之外或者使用数据库存储，实际情况则要要看具体业务实现方案。

也可以查看 [官方日志文档](https://laravel-china.org/docs/laravel/5.6/logging)。

如果选择在线数据库的方式可以看下用 `Python` 实现的一款开源插件[Loglog](https://www.oschina.net/p/loglog), 初版有些简陋，有待改进的方案，但是由于时间有限，各位可以在原有的思路上自行优化使用。

### 2.12 拓展插件使用

#### *Artisan 脚手架 命令行*

详细的介绍还是要看 [官方的文档](https://laravel-china.org/docs/laravel/5.6/artisan).

这里我只介绍几个实际开发中会经常使用到的几个功能方便手动的开发。

> 1.创建模型: `php artisan make:model User`; 如果要在生成模型时生成 数据库迁移 ，可以使用 `--migration` 或 `-m` 选项: `php artisan make:model User -m`；

> 2.创建控制器: `php artisan make:controller PhotoController --resource`；

> 3.创建Middleware中间件: `php artisan make:middleware CheckAge`；

> 注:其他的会在后期慢慢学习追加

#### *广播系统*

`Laravel` 通过 `WebSocket` 连接来使「广播」事件, 广播事件允许你在服务端代码和客户端 JavaScript 应用之间共享相同的事件名。

***配置***

所有关于事件广播的配置都被保存在 `config/broadcasting.php` 文件中。 `Laravel` 自带了几个广播驱动器：`Pusher`、 `Redis`，和一个用于本地开发与调试的 `log` 驱动器。另外，还有一个 null 驱动器可以让你完全关闭广播功能。每一个驱动的示例配置都可以在 config/broadcasting.php 文件中被找到。

广播服务提供者
在对事件进行广播之前，你必须先注册 App\Providers\BroadcastServiceProvider。对于一个全新安装的 Laravel 应用程序，你只需在 config/app.php 配置文件的 providers 数组中取消对该提供者的注释即可。该提供者将允许你注册广播授权路由和回调。

由于本地使用的是 Laravel5.2 没有提供 广播系统 功能这里直接导向[Laravel5.6的官方文档](https://laravel-china.org/docs/laravel/5.6/broadcasting)。

#### *缓存系统*

`Laravel` 为各种后端缓存提供丰富而统一的 API，而其配置信息位于 `config/cache.php` 文件中，你可以指定默认的缓存驱动程序。`Laravel` 支持当前流行的后端缓存，例如 `Memcached` 和 `Redis`。

[官方的文档可以参考这里](https://laravel-china.org/docs/laravel/5.6/cache)。这里我们只简单的使用Redis来实现一下功能；

由于没有安装Laravel对应的支持插件这里我们用composer来安装一下：`composer require "predis/predis:~1.0"`，安装完成即可开始使用。

***配置***

在配置文件 `.env` 和 对应的redis配置文件添加完信息

使用 `use Illuminate\Support\Facades\Cache` 来获取缓存实例
```php
use Illuminate\Support\Facades\Cache;
$value = Cache::get('key');
```
当然，也可一次访问多个缓存实例
```php
use Illuminate\Support\Facades\Cache;
// 文件缓存
$val = Cache::store('file')->get('foo');

// redis缓存
Cache::store('redis')->put('bar', 'baz', 10);

// ------不指定时走默认的-----
// 获取数据
$value = Cache::get('key');
$value = Cache::get('key', 'default'); // 当获取不到时采用默认值

// 确认是否存在
if (Cache::has('key')) {
    //
}

// 递增与递减值
Cache::increment('key');
Cache::increment('key', $amount);
Cache::decrement('key');
Cache::decrement('key', $amount);

// 获取和存储 缓存时间$minutes
// 解析：操作先去缓存中获取信息，当缓存中没有要获取的信息时，就会去数据库去取，并返回数据同时在缓存中缓存一份数据。
$value = Cache::remember('users', $minutes, function () {
    return DB::table('users')->get();
});
// 或者永久缓存
$value = Cache::rememberForever('users', function() {
    return DB::table('users')->get();
});

// 获取之后删除数据
$value = Cache::pull('key');

// 在缓存中存储数据
Cache::put('key', 'value', $minutes);

// 过期时间设置
$expiresAt = now()->addMinutes(10);
Cache::put('key', 'value', $expiresAt);

// add 方法将不存在于缓存中的数据放入缓存中，如果存放成功返回 true ，否则返回 false
Cache::add('key', 'value', $minutes);

// 数据永久存储
// forever 方法可以用来将数据永久存入缓存中。
Cache::forever('key', 'value');

// 删除缓存中的数据
Cache::forget('key');
// flush 方法清空所有缓存
Cache::flush();

// Cache 辅助函数 控制器存在一个全局的辅助函数 
$value = cache('key');
cache(['key' => 'value'], $minutes);
cache(['key' => 'value'], now()->addSeconds(10));

```
具体的使用方法看上面代码的使用。

***sesseion***

代码实例

```php
public function session(Request $request)
{
	// --------------------------全局函数 sesseion();
	// --------------------------HTTP 请求实例 $request->session()
	// 1、session 
	// 存储
	session(['kkk' => 'zuoliguanghhhh']);
	// 获取
	$v = session('kkk');
	// 指定默认值
	$v = session('qqq', 'default');

	// var_dump($v);

	// 2、$request->session() 存储
	// 存储
	$request->session()->put('aaa', 'zuoliguangaaaa');
	$request->session()->put('bbb', 'zuoliguangbbbb');
	// 添加新值
	$request->session()->push('user.teams', 'developers');

	// 删除
	$v = $request->session()->pull('bbb', 'default_bbb');

	// var_dump($v);

	// 获取
	$v = $request->session()->get('aaa'); // 获取指定信息
	$vs = $request->session()->all(); // 获取所有信息
	
    // 删除
    $request->session()->forget('key');
    // 全部删除
    $request->session()->flush();
    
	var_dump($vs);
}
```

#### *集合 Collection （处理数组的高级封装）*

`Illuminate\Support\Collection` 类提供了一个更具可读性的、更便于处理数组数据的封装。

例子：
```php
// 创建集合
$collection = collect(['taylor', 'abigail', null])
// 将每个元素大写
->map(function ($name) {
    return strtoupper($name);
})
// 将空元素去掉
->reject(function ($name) {
    return empty($name);
});
```

***集合拓展***

```php
use Illuminate\Support\Str;// 加载str类

Collection::macro('toUpper', function () {
    return $this->map(function ($value) {
        return Str::upper($value); // 将每个元素大写操作
    });
});

$collection = collect(['first', 'second']);
$upper = $collection->toUpper();
// 结果 ['FIRST', 'SECOND']
```

> Collection 类每个可用的方法。记住，所有方法都可以以链式访问的形式优雅地操纵数组。而且，几乎所有的方法都会返回新的 Collection 实例，允许你在必要时保存集合的原始副本。

[Collection可用的方法](https://laravel-china.org/docs/laravel/5.6/collections#26e4a9), 具体方法可查看链接详情。

#### *事件系统*

Laravel 的事件提供了一个简单的观察者实现，能够订阅和监听应用中发生的各种事件。事件类保存在 `app/Events` 目录中，而这些事件的的监听器则被保存在 `app/Listeners` 目录下。

我们先来实现一个简单的时间监听装置。

`Project/app/Events/Event.php` 中添加监听映射
```php
/**
 * 应用程序的事件侦听器映射。
 *
 * @var array
 */
protected $listen = [
	'App\Events\TestEvent' => [
		'App\Listeners\TestListener',
	],
];
```

`app/Events` 目录下添加 `TestEvent`

```php
namespace App\Events;

use App\Events\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestEvent extends Event
{
    use SerializesModels;

    public $testInfo = 'This Is Test Info!';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($str='')
    {
        $this->testInfo .= $str;
        Log::notice($this->testInfo.'-Event ');
    }
}
```

`app/Listeners` 目录下添加 `TestListener`

```php
namespace App\Listeners;

use App\Events\TestEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TestEvent  $event
     * @return void
     */
    public function handle(TestEvent $event)
    {
        $message = $event->testInfo;
        Log::warning($message.'-Listener ');
    }
}
```

这里将执行的结果直接写入Log文件中查看

控制器中调用的方式, 使用全局 `event` 辅助函数来触发事件

```php
use App\Events\TestEvent;

event(new TestEvent('zuoliguang'));
```
或者
```php
use Event;
use App\Events\TestEvent;

Event::fire(new TestEvent('zuoliguang'));
```


#### *文件存储*

该部分内容可以查看 <a href="#uploadFile">文件上传<a> 部分内容

或者查看 [官方文档](https://laravel-china.org/docs/laravel/5.6/filesystem), 文档中也可以尝试其他的方式来保存。

#### *辅助函数、框架全局Helper函数*

具体可查看 [官方文档](https://laravel-china.org/docs/laravel/5.6/helpers)

#### *消息队列*

Laravel 队列为不同的队列后台服务提供了统一的 API，比如 Beanstalk, Amazon SQS, Redis, 甚至是关系型数据库。

队列配置文件存储在 `config/queue.php`。在这个文件中，你可以找到框架包含的所有队列驱动的连接配置： `database`, `Beanstalkd`, `Amazon SQS`, `Redis`，和一个直接执行任务的同步驱动（本地使用）。 还包括了一个 null 队列驱动用于直接丢弃队列任务。

具体可查看 [官方文档](https://laravel-china.org/docs/laravel/5.6/queues)

#### *消息辅助（邮件、短信、数据库）*

具体可查看 [官方文档](https://laravel-china.org/docs/laravel/5.6/notifications)

#### *Laravel 拓展包开发*

具体可查看 [官方文档](https://laravel-china.org/docs/laravel/5.6/packages)

