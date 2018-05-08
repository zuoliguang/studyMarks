最近发现好多企业级开发网站均开始使用 Laravel 框架，业余时间来研究一下并学习下该框架的使用

该文档使用的是Laravel5.2的版本来演示代码内容，最新版本功能可参考对应的 [框架文档](https://laravel-china.org/docs/laravel/5.6/installation)

------

**注意** :该笔记写完后，即可以将以此在正式环境中使用,包括框架的一些拓展操作.

参考框架结构图

![laravel5.6](https://github.com/zuoliguang/studyMarks/blob/master/images/laravel5.6.jpg?raw=true)

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
