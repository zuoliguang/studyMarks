### MySQL server has gone away 问题的解决方法

`mysql`出现`ERROR : (2006, 'MySQL server has gone away')` 的问题意思就是指`client`和`MySQL server`之间的链接断开了。

产生这个问题的原因有很多，总结下网上的分析：

***原因一. MySQL 服务宕了***

判断是否属于这个原因的方法很简单，进入mysql控制台，查看mysql的运行时长

```mysql
mysql> show global status like 'uptime';
+---------------+---------+
| Variable_name | Value   |
+---------------+---------+
| Uptime        | 3414707 |
+---------------+---------+
1 row in set
```

或者查看MySQL的报错日志，看看有没有重启的信息

如果uptime数值很大，表明mysql服务运行了很久了。说明最近服务没有重启过。

如果日志没有相关信息，也表名mysql服务最近没有重启过，可以继续检查下面几项内容。

***原因二. mysql连接超时***

即某个mysql长连接很久没有新的请求发起，达到了server端的timeout，被server强行关闭。

此后再通过这个connection发起查询的时候，就会报错server has gone away

（大部分PHP脚本就是属于此类）

```mysql
mysql> show global variables like '%timeout';
+----------------------------+----------+
| Variable_name              | Value    |
+----------------------------+----------+
| connect_timeout            | 10       |
| delayed_insert_timeout     | 300      |
| innodb_lock_wait_timeout   | 50       |
| innodb_rollback_on_timeout | OFF      |
| interactive_timeout        | 28800    |
| lock_wait_timeout          | 31536000 |
| net_read_timeout           | 30       |
| net_write_timeout          | 60       |
| slave_net_timeout          | 3600     |
| wait_timeout               | 28800    |
+----------------------------+----------+
10 rows in set
```

`wait_timeout` 是28800秒，即`mysql`链接在无操作28800秒后被自动关闭

***原因三. mysql请求链接进程被主动kill***

这种情况和原因二相似，只是一个是人为一个是MYSQL自己的动作

```mysql
mysql> show global status like 'com_kill';
+---------------+-------+
| Variable_name | Value |
+---------------+-------+
| Com_kill      | 21    |
+---------------+-------+
1 row in set
```

***原因四. Your SQL statement was too large.***

当查询的结果集超过 `max_allowed_packet` 也会出现这样的报错。定位方法是打出相关报错的语句。

用`select * into outfile` 的方式导出到文件，查看文件大小是否超过 `max_allowed_packet` ，如果超过则需要调整参数，或者优化语句。

```mysql
mysql> show global variables like 'max_allowed_packet';
+--------------------+---------+
| Variable_name      | Value   |
+--------------------+---------+
| max_allowed_packet | 1048576 |
+--------------------+---------+
1 row in set (0.00 sec)
```

修改参数：

```mysql
mysql> set global max_allowed_packet=1024*1024*16;
mysql> show global variables like 'max_allowed_packet';
+--------------------+----------+
| Variable_name      | Value    |
+--------------------+----------+
| max_allowed_packet | 16777216 |
+--------------------+----------+
1 row in set (0.00 sec)
```

### 解决MySQL server has gone away 

***1、应用程序（比如PHP）长时间的执行批量的MYSQL语句。最常见的就是采集或者新旧数据转化。***

解决方案： 

```php
在my.cnf文件中添加或者修改以下两个变量： 
wait_timeout=2880000 
interactive_timeout = 2880000 
关于两个变量的具体说明可以google或者看官方手册。
如果不能修改my.cnf，则可以在连接数据库的时候设置CLIENT_INTERACTIVE，比如： 
sql = "set interactive_timeout=24*3600"; 
mysql_real_query(...) 
```

> 增加你的 `wait-timeout`值，这个参数是在`my.cnf`(在`Windows`下台下面是`my.ini`）中设置，我的数据库负荷稍微大一点，所以，我设置的值 为10，（这个值的单位是秒，意思是当一个数据库连接在10秒钟内没有任何操作的话，就会强行关闭，我使用的不是永久链接 （`mysql_pconnect`),用的是`mysql_connect`,关于这个`wait-timeout`的效果你可以在MySQL的进程列表中看到 （`show processlist`) ），你可以把这个`wait-timeout`设置成更大，比如300秒，情况由你的服务器和站点来定。

> 这也是我个人认为最好的方法，即检查 `MySQL` 的链接状态，使其重新链接。 可能大家都知道有 `mysql_ping` 这么一个函数，在很多资料中都说这个`mysql_ping` 的 `API` 会检查数据库是否链接，如果是断开的话会尝试重新连接，但在我的测试过程中发现事实并不是这样子的，是有条件的，必须要通过  `mysql_options` 这个 `API` 传递相关参数，让 `MYSQL` 有断开自动链接的选项（`MySQL`默认为不自动连接），但我测试中发现 `PHP` 的 `MySQL` 的 `API` 中并不带这个函数。但 `mysql_ping` 这个函数还是终于能用得上的，只是要在其中有一个小小的操作技巧： 

代码如下：

```php
function ping(){ 
	if(!mysql_ping($this->link)){ 
		mysql_close($this->link); //注意：一定要先执行数据库关闭，这是关键 
		$this->connect(); 
	} 
} 
```

我需要调用这个函数的代码可能是这样子的 

```php
$str = file_get_contents('http://url.com'); 
$db->ping();//经过前面的网页抓取后，或者会导致数据库连接关闭,检查并重新连接 
$db->query('select * from table'); 
```

> ping()这个函数先检测数据连接是否正常，如果被关闭，整个把当前脚本的MYSQL实例关闭，再重新连接。 
> 经过这样处理后，可以非常有效的解决MySQL server has gone away这样的问题，而且不会对系统造成额外的开销。 

***2、执行一个SQL，但SQL语句过大或者语句中含有BLOB或者longblob字段。比如，图片数据的处理***

解决方案： 

```php
在my.cnf文件中添加或者修改以下变量： 
max_allowed_packet = 10M (也可以设置自己需要的大小) 
max_allowed_packet 参数的作用是，用来控制其通信缓冲区的最大长度。 
```



