最近发现好多企业级开发网站均开始使用 Laravel 框架，业余时间来研究一下并学习下该框架的使用

该文档使用的是Laravel5.2的版本来演示代码内容，最新版本功能可参考对应的 [框架文档](https://laravel-china.org/docs/laravel/5.6/installation)

------

**注意** :该笔记写完后，即可以将以此在正式环境中使用,包括框架的一些拓展操作.

参考框架结构图

<!-- ![laravel5.6](https://github.com/zuoliguang/studyMarks/blob/master/images/laravel5.6.jpg?raw=true) -->

在该图中摘出了自己的即将学习的任务列表，展开对Laravel框架的系统学习。

## 1、入门
### 1.1 安装
通过使用 Composer 安装 Laravel 安装器：

```composer global require "laravel/installer"```

安装完成后， laravel new 命令会在您指定的目录创建一个全新的 Laravel 项目

```laravel new blog```

或者，你也可以在终端中运行 create-project 命令来安装 Laravel：

```composer create-project --prefer-dist laravel/laravel blog```

本地开发环境时，可直接执行 **php artisan serve** 该命令会在 http://localhost:8000 上启动开发服务器

### 1.2 了解框架的文件目录结构
> *  app        包含应用程序的核心代码，应用中几乎所有的类都应该放在这里；
> *  bootstrap  包含引导框架并配置自动加载的文件，该目录还包含了一个 cache 目录，存放着框架生成的用来提升性能的文件，比如路由和服务缓存文件；
> *  config     包含应用程序所有的配置文件；
> *  database   包含数据填充和迁移文件，还可以把它作为 SQLite 数据库存放目录；
> *  public     包含了入口文件 index.php，还包含了一些资源文件（如图片、JavaScript 和 CSS）；
> *  resources  包含了视图和未编译的资源文件（如 LESS、SASS 或 JavaScript），还包含你所有的语言文件；
> *  storage    包含编译的 Blade 模板、基于文件的会话和文件缓存、以及框架生成的其他文件；
> *  tests      包含自动化测试文件；
> *  vendor     包含你的 Composer 依赖包；

### 1.3 生产环境
控制项目的配置文件 `.env` 中 `APP_DEBUG=true` 来切换项目状态;

## 2、HelloWorld 测试学习
### 2.1 路由
#### 2.1.1 直接在路由中返回结果
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
  或者指定到固定的控制器来处理
  将 `http:://ip/study` 指向 `Project/app/Http/Controllers/Study/IndexController.ph`p 下的 `index` 方法
  ```php
  Route::get('/study', 'Study\IndexController@index');
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

