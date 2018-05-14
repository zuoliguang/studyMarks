## Swoole学习 

> 该学习笔记参照 [PHP-MSF开发手册](https://legacy.gitbook.com/book/pinguo/php-msf-docs/details) 学习，并依据提供的解决方案解决该过程遇到的问题.

> 或者参照 [Swoole文档中心](https://wiki.swoole.com/wiki/page/1.html) 安装.

> 安装的环境 CentOS6.5、PHP7.7.4（ 使用[lnmp1.4](https://lnmp.org/),安装集合环境 ）.

这里讲解的主要是如何在 PHP 中添加 Swoole 拓展。

#### 1. 下载并安装软件

因为环境的要求

* Linux，FreeBSD，MacOS(有兼容问题)
* Linux内核版本2.3.32以上(支持epoll)
* PHP-7.0及以上版本（推荐使用PHP-7.1）
* gcc-4.4以上版本
* swoole-1.9.15及以上版本（暂不支持Swoole-2.0）
* hiredis-0.13.3
* yac
* phpredis
* composer

软件下载地址

* https://github.com/swoole/swoole-src/releases
* http://pecl.php.net/package/swoole
* http://git.oschina.net/swoole/swoole

这里下载的是 swoole-1.9.18的版本: `wget http://pecl.php.net/get/swoole-1.9.18.tgz`

解压 `tar zxf swoole-1.9.18.tgz`

进到解压文件 `cd swoole-1.9.18`

`/usr/local/php/bin/phpize`

`./configure --with-php-config=/usr/local/php/bin/php-config`

编译并安装

`make`

`make install`

#### 2. PHP加载

修改PHP拓展部分 `vim /usr/local/php/etc/php.ini`

在添加拓展部分 添加 `extension=swoole.so` 

重启 PHP `service php-fpm restart`

查看 `php -m` 或者 `phpinfo()` 即可看到 Swoole 拓展.

#### 3. 测试代码

服务端代码：

```php
# 创建TCP 服务端
$server = new swoole_server("192.168.147.138", 55152);
# 链接端口时触发操作
$server->on("connect", function($server, $e){
    echo "Clinet:connect.\n";
});
# 返回信息是操作
$server->on("receive", function($server, $e, $from, $data){
    $server->send($e, "Swoole: from-".$from." ; Data:".$data);
});
# 关闭端口时触发操作
$server->on("close", function($server, $e){
    echo "Client:close.\n";
});
# 开启端口监听
$server->start();
```

客户端代码:
```php
# 创建TCP 客户端
$client = new swoole_client(SWOOLE_SOCK_TCP);
# 链接服务端
if(!$client->connect("192.168.147.138", 55152, -1)){
    exit("connect failed. Error: {$client->errCode}\n");
}
# 向端口发送信息
$client->send("hello world\n");
# 端口返回信息
echo $client->recv();
# 关闭客户端
$client->close();
```
> 请勿在使用 `swoole_server` 之前调用其他异步 IO 的 API，否则将无法创建 `swoole_server`.

操作完已上操作，我们访问客户端地址就会看到链接的返回信息 `hello world`.

那已上操作说明我们通过 `Swoole` 创建了 TCP 端口并通过该端口进行了简单的通信.





