## 1、简介
Blade 是 Laravel 提供的一个非常简单但很强大的模板引擎，不同于其他流行的 PHP 模板引擎，Blade 在视图中并不约束你使用 PHP 原生代码。所有的 Blade 视图都会被编译成原生 PHP 代码并缓存起来直到被修改，这意味着对应用的性能而言 Blade 基本上是零开销。Blade 视图文件使用 .blade.php 文件扩展并存放在 resources/views 目录下。

## 2、模板继承
### 定义布局
使用 Blade 的两个最大优点是模板继承和切片，开始之前让我们先看一个例子。首先，我们检测一个“主”页面布局，由于大多数 Web 应用在不同页面中使用同一个布局，可以很方便的将这个布局定义为一个单独的 Blade 页面：

```php
<!-- 存放在 resources/views/layouts/master.blade.php -->

<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @show

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
```

> 正如你所看到的，该文件包含典型的 HTML 标记，然而，注意 @section 和 @yield 指令，前者正如其名字所暗示的，定义了一个内容的片段，而后者用于显示给定片段的内容。

> 现在我们已经为应用定义了一个布局，接下来让我们定义继承该布局的子页面吧。

### 扩展布局
> 定义子页面的时候，可以使用 Blade 的 @extends 指令来指定子页面所继承的布局，继承一个 Blade 布局的视图将会使用 @section 指令注入内容到布局的片段中，记住，如上面例子所示，这些片段的内容将会显示在布局中使用@yield 的地方：

```php
<!-- 存放在 resources/views/child.blade.php -->

@extends('layouts.master')

@section('title', 'Page Title')

@section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p>This is my body content.</p>
@endsection
```
在本例中，sidebar 片段使用 @parent 指令来追加（而非覆盖）内容到布局中 sidebar，@parent 指令在视图渲染时将会被布局中的内容替换。

当然，和原生 PHP 视图一样，Blade 视图可以通过 view 方法直接从路由中返回：

```php
Route::get('blade', function () {
   return view('child');
});
```

## 3、数据显示
可以通过两个花括号包裹变量来显示传递到视图的数据，比如，如果给出如下路由：

```php
Route::get('greeting', function () {
    return view('welcome', ['name' => 'Samantha']);
});
```

那么可以通过如下方式显示 name 变量的内容：

```php
Hello, {{ $name }}.
```
当然，不限制显示到视图中的变量内容，你还可以输出任何 PHP 函数，实际上，可以将任何 PHP 代码放到 Blade 模板语句中：

```php
The current UNIX timestamp is {{ time() }}.
```
> 注：Blade 的 {{}} 语句已经经过 PHP 的 htmlentities 函数处理以避免 XSS 攻击。

### 输出存在的数据

有时候你想要输出一个变量，但是不确定该变量是否被设置，我们可以通过如下 PHP 代码：

```php
{{ isset($name) ? $name : 'Default' }}
```
除了使用三元运算符，Blade 还提供了更简单的方式：

```php
{{ $name or 'Default' }}
```
在本例中，如果 $name 变量存在，其值将会显示，否则将会显示“Default”。

### 显示原生数据

默认情况下，Blade 的 {{ }} 语句已经通过 PHP 的 htmlentities 函数处理以避免 XSS 攻击，如果你不想要数据被处理，可以使用如下语法：

```php
Hello, {!! $name !!}.
```
> 注：输出用户提供的内容时要当心，对用户提供的内容总是要使用双花括号包裹以避免直接输出 HTML 代码。

## Blade & JavaScript 框架
由于很多 JavaScript 框架也是用花括号来表示要显示在浏览器中的表达式，可以使用 @ 符号来告诉 Blade 渲染引擎该表达式应该保持原生格式不作改动。比如：

```php
<h1>Laravel</h1>
Hello, @{{ name }}.
```
在本例中，@ 符将会被 Blade 移除，但是，{{ name }} 表达式将会保持不变，避免被 JavaScript 框架渲染。

### @verbatim指令

如果你在模板中很大一部分显示JavaScript变量，那么可以将这部分HTML封装在@verbatim指令中，这样就不需要在每个Blade输出表达式前加上@前缀：

```php
@verbatim
    <div class="container">
        Hello, {{ name }}.
    </div>
@endverbatim
```
## 4、流程控制
除了模板继承和数据显示之外，Blade 还为常用的 PHP 流程控制提供了便利操作，比如条件语句和循环，这些快捷操作提供了一个干净、简单的方式来处理 PHP 的流程控制，同时保持和 PHP 相应语句的相似。

### If 语句
可以使用 @if , @elseif ,  @else 和 @endif 来构造 if 语句，这些指令函数和 PHP 的相同：

```php
@if (count($records) === 1)
    I have one record!
@elseif (count($records) > 1)
    I have multiple records!
@else
    I don't have any records!
@endif
```
为方便起见，Blade 还提供了 @unless 指令：
```php
@unless (Auth::check())
    You are not signed in.
@endunless
```
### 循环
除了条件语句，Blade 还提供了简单指令处理 PHP 支持的循环结构，同样，这些指令函数和 PHP 的一样：

```php
@for ($i = 0; $i < 10; $i++)
    The current value is {{ $i }}
@endfor

@foreach ($users as $user)
    <p>This is user {{ $user->id }}</p>
@endforeach

@forelse ($users as $user)
    <li>{{ $user->name }}</li>
@empty
    <p>No users</p>
@endforelse

@while (true)
    <p>I'm looping forever.</p>
@endwhile
```
在循环的时候可以使用$loop变量获取循环信息，例如是否是循环的第一个或最后一个迭代：
```php
@foreach ($users as $user)
    @if ($user->type == 1)
        @continue
    @endif

    <li>{{ $user->name }}</li>

    @if ($user->number == 5)
        @break
    @endif
@endforeach
```
还可以使用指令声明来引入条件：
```php
@foreach ($users as $user)
    @continue($user->type == 1)

        <li>{{ $user->name }}</li>

    @break($user->number == 5)
@endforeach
```
### $loop变量
在循环的时候，可以在循环体中使用$loop变量，该变量提供了一些有用的信息，比如当前循环索引，以及当前循环是不是第一个或最后一个迭代：
```php
@foreach ($users as $user)
    @if ($loop->first)
        This is the first iteration.
    @endif

    @if ($loop->last)
        This is the last iteration.
    @endif

    <p>This is user {{ $user->id }}</p>
@endforeach
```
如果你身处嵌套循环，可以通过 $loop 变量的 parent 属性访问父级循环：
```php
@foreach ($users as $user)
    @foreach ($user->posts as $post)
        @if ($loop->parent->first)
            This is first iteration of the parent loop.
        @endif
    @endforeach
@endforeach
```
$loop 变量还提供了一些有用的其他属性：
|属性	               |描述                      |
|--------------------|-------------------------|
|$loop->index	    |当前循环迭代索引 (从0开始). |
|$loop->iteration	|当前循环迭代 (从1开始).     |
|$loop->remaining	|当前循环剩余的迭代          |
|$loop->count	    |迭代数组元素的总数量         |
|$loop->first	    |是否是当前循环的第一个迭代  |
|$loop->last	    |是否是当前循环的最后一个迭代 |
|$loop->depth	    |当前循环的嵌套层级          |
|$loop->parent	    |嵌套循环中的父级循环变量     |

> 注释:Blade 还允许你在视图中定义注释，然而，不同于 HTML 注释，Blade 注释并不会包含到 HTML 中被返回：

```php
{{-- This comment will not be present in the rendered HTML --}}
```
### 5、包含子视图
Blade 的 @include 指令允许你很简单的在一个视图中包含另一个 Blade 视图，所有父级视图中变量在被包含的子视图中依然有效：
```php
<div>
    @include('shared.errors')

    <form>
        <!-- Form Contents -->
    </form>
</div>
```
尽管被包含的视图继承所有父视图中的数据，你还可以传递额外参数到被包含的视图：
```php
@include('view.name', ['some' => 'data'])
```
> 注：不要在 Blade 视图中使用 __DIR__ 和 __FILE__ 常量，因为它们会指向缓存视图的路径。

### 为集合渲染视图
你可以使用 Blade 的 @each 指令通过一行代码循环引入多个局部视图：
```php
@each('view.name', $jobs, 'job')
```
该指令的第一个参数是数组或集合中每个元素要渲染的局部视图，第二个参数是你希望迭代的数组或集合，第三个参数是要分配给当前视图的变量名。举个例子，如果你要迭代一个 jobs 数组，通常你需要在局部视图中访问 $job 变量。在局部视图中可以通过 key 变量访问当前迭代的键。

你还可以传递第四个参数到 @each 指令，该参数用于指定给定数组为空时渲染的视图：
```php
@each('view.name', $jobs, 'job', 'view.empty')
```
## 6、堆栈
Blade允许你推送内容到命名堆栈，以便在其他视图或布局中渲染。这在子视图中引入指定JavaScript库时很有用：
```php
@push('scripts')
    <script src="/example.js"></script>
@endpush
```
推送次数不限，要渲染完整的堆栈内容，传递堆栈名称到 @stack 指令即可：
```php
<head>
    <!-- Head Contents -->

    @stack('scripts')
</head>
```
## 7、服务注入
@inject 指令可以用于从服务容器中获取服务，传递给 @inject 的第一个参数是服务将要被放置到的变量名，第二个参数是要解析的服务类名或接口名：
```php
@inject('metrics', 'App\Services\MetricsService')

<div>
    Monthly Revenue: {{ $metrics->monthlyRevenue() }}.
</div>
```
## 8、扩展 Blade
Blade 甚至还允许你自定义指令，可以使用 directive 方法来注册一个指令。当 Blade 编译器遇到该指令，将会传入参数并调用提供的回调。

下面的例子创建了一个 @datetime($var) 指令格式化给定的 DateTime 的实例 $var：
```php
<?php
namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('datetime', function($expression) {
            return "<?php echo $expression->format('m/d/Y H:i'); ?>";
        });
    }

    /**
     * 在容器中注册绑定.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```
正如你所看到的，我们可以将format方法应用到任何传入指令的表达式上。最终该指令生成的 PHP 代码如下：
```php
<?php echo $var->format('m/d/Y H:i'); ?>
```
> 注：更新完Blade指令逻辑后，必须删除所有的Blade缓存视图。缓存的Blade视图可以通过Artisan命令 view:clear 移除。
