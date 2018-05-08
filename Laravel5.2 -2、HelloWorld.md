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
  资源路由器（将剩余未定义的方法按照方法名定义）
```php
Route::resource('study/index', 'Study\IndexController');
// 或者
Route::resources([
	'photos' => 'PhotoController',
	'posts' => 'PostController'
]);
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
或者直接打印 `$request` 查看内容

**使用Route** 如果要获取的是路径信息 可以使用 `use Illuminate\Support\Facades\Route` 来直接获得
```php
$route = Route::current();
$name = Route::currentRouteName();
$action = Route::currentRouteAction();
```
### 2.5 Responses 响应
### 2.6 文件上传
### 2.7 URL 处理
### 2.8 错误处理
### 2.9 模板（数据模型）
### 2.10 视图 及 前端资源
### 2.11 日志
### 2.12 拓展插件使用
#### *Artisan 脚手架 命令行*
#### *广播系统*
#### *缓存系统*
#### *集合 Collection （处理数组的高级封装）*
#### *事件系统*
#### *文件存储*
#### *辅助函数、框架全局Helper函数*
#### *消息辅助（邮件、短信、数据库）*
#### *消息队列*
#### *调度任务 Cron*
#### *Laravel 拓展包开发*
