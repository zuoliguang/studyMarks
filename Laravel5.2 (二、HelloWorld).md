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
  控制器路由命名及使用, `Project/app/Http/routes.php`中
  
  ```php
  Route::get('/study', 'Study\IndexController@index')->name('index');
  ```
  
  `Project/app/Http/Controllers/Study/IndexController.php` 中调用方式（跳转使用）
  
  ```php
  return redirect()->route('index');
  ```
  资源路由器（将剩余未定义的方法按照方法名定义）
  ```php
  Route::resource('study/index', 'Study\IndexController');
  ```
  
### 2.2 middleware 中间件
### 2.3 CSRF 保护、表单验证
### 2.4 控制器
### 2.5 Request 请求
### 2.6 Responses 响应
### 2.7 文件上传
### 2.8 URL 处理
### 2.9 错误处理
### 2.10 模板（数据模型）
### 2.11 视图 及 前端资源
### 2.12 日志
### 2.13 拓展插件使用
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

## 3、核心框架
### 3.1 入口
### 3.2 初始化核心引导
### 3.3 中间件
### 3.4 容器
### 3.5 静态代理
### 3.6 Facades 代理类

## 4、数据库
### 4.1 原生数据库操作
### 4.2 Eloquent ORM 模型
### 4.3 查询构造器
### 4.4 分页
### 4.5 初始化测试数据
### 4.6 迁移数据

## 5、官方拓展功能
### 5.1 全文搜索
### 5.2 监控应用队列的吞吐状态
### 5.3 社会化登录
