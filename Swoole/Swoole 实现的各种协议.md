
### HTTP Server

```php
$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$http->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$http->start();
```

### WebSocket Server

```php
$server = new swoole_websocket_server("127.0.0.1", 9502);

$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
    $server->push($frame->fd, json_encode(["hello", "world"]));
});

$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();
```

### TCP Server

```php
$server = new swoole_server("127.0.0.1", 9503);
$server->on('connect', function ($server, $fd){
    echo "connection open: {$fd}\n";
});
$server->on('receive', function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "Swoole: {$data}");
    $server->close($fd);
});
$server->on('close', function ($server, $fd) {
    echo "connection close: {$fd}\n";
});
$server->start();
```

### TCP Client

```php
$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
$client->on("connect", function($cli) {
    $cli->send("hello world\n");
});
$client->on("receive", function($cli, $data){
    echo "received: {$data}\n";
});
$client->on("error", function($cli){
    echo "connect failed\n";
});
$client->on("close", function($cli){
    echo "connection close\n";
});
$client->connect("127.0.0.1", 9501, 0.5);
```

### Async Redis / HTTP / WebSocket Client

```php
$redis = new Swoole\Redis;
$redis->connect('127.0.0.1', 6379, function ($redis, $result) {
    $redis->set('test_key', 'value', function ($redis, $result) {
        $redis->get('test_key', function ($redis, $result) {
            var_dump($result);
        });
    });
});

$cli = new Swoole\Http\Client('127.0.0.1', 80);
$cli->setHeaders(array('User-Agent' => 'swoole-http-client'));
$cli->setCookies(array('test' => 'value'));

$cli->post('/dump.php', array("test" => 'abc'), function ($cli) {
    var_dump($cli->body);
    $cli->get('/index.php', function ($cli) {
        var_dump($cli->cookies);
        var_dump($cli->headers);
    });
});
```

### Tasks

```php
$server = new swoole_server("127.0.0.1", 9502);
$server->set(array('task_worker_num' => 4));
$server->on('receive', function($server, $fd, $reactor_id, $data) {
    $task_id = $server->task("Async");
    echo "Dispath AsyncTask: [id=$task_id]\n";
});
$server->on('task', function ($server, $task_id, $reactor_id, $data) {
    echo "New AsyncTask[id=$task_id]\n";
    $server->finish("$data -> OK");
});
$server->on('finish', function ($server, $task_id, $data) {
    echo "AsyncTask[$task_id] finished: {$data}\n";
});
$server->start();
```

[点击这里](https://pinguo.gitbooks.io/php-msf-docs/content/chapter-2/2.2-swoole.html) 可查看更多 `Swoole` 实例，虽然有些也想不到实际应用的场景，多看评论并结合自己的经验或许会有答案。

测试之前，还有可参考的官方参考代码以及详细的讲解 
* [代码实例](https://github.com/pinguo/php-msf-demo)
* [详细讲解](https://pinguo.gitbooks.io/php-msf-docs/chapter-4/4.1-%E7%BB%93%E6%9E%84%E6%A6%82%E8%BF%B0.html)
