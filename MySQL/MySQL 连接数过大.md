### 数据库连接数过大时操作

***1、查看数据链接情况***

`mysql -uroot -p123456 -e 'show status' | grep -i threads`
```
Delayed_insert_threads    0
Slow_launch_threads    0
Threads_cached    1
Threads_connected    1
Threads_created    2
Threads_running    1 ##当前连接数
```

或者

```mysql
show status like 'Threads%';
+-------------------+-------+
| Variable_name    | Value |
+-------------------+-------+
| Threads_cached    | 1    |
| Threads_connected | 1    |
| Threads_created  | 2    |
| Threads_running  | 1    |   ###当前连接数
+-------------------+-------+
4 rows in set (0.00 sec)
```

***2、查看最大连接数***

`mysql -uroot -p123456 -e 'show variables' | grep max_connections`

或者

```mysql
show global variables like 'max_conn%';
+--------------------+-------+
| Variable_name      | Value |
+--------------------+-------+
| max_connect_errors | 10    |
| max_connections    | 500  |## 最大连接数
+--------------------+-------+
2 rows in set (0.00 sec)
```

解决方案:
原则：尽量不重启的情况下解决问题

1、用gdb工具 不用进入数据库，不用重启数据库 方法如下：
```
[root@xxx bin]# gdb -p $(cat /data/mydata/xxx.pid) -ex "set max_connections=500" -batch  
[New LWP 7667]
[New LWP 4816]
[New LWP 341]
[New LWP 338]
[New LWP 337]
[New LWP 336]
[New LWP 335]
[New LWP 331]
[New LWP 330]
[New LWP 329]
[New LWP 328]
[New LWP 327]
[New LWP 326]
[New LWP 325]
[New LWP 324]
[New LWP 323]
[New LWP 322]
[Thread debugging using libthread_db enabled]
0x00000035654df1b3 in poll () from /lib64/libc.so.6
```

查看mysql pid位置的方法

```
mysql> show variables like '%pid%';
+---------------+----------------------+
| Variable_name | Value                |
+---------------+----------------------+
| pid_file      | /data/mydata/xxx.pid |
+---------------+----------------------+
1 row in set (0.00 sec)
```
> 注意：修改完毕后 ，尝试重新进入数据库，并查看链接数，这种方法设置后，只是暂时的，数据库重启后，会变为原来的数值，要想永久，设置完后修改配置文件my.cnf。

2、进入数据库

设置新的最大连接数为200：mysql> set global max_connections=200

显示当前运行的Query：mysql> show processlist

显示当前状态：mysql> show status

退出客户端：mysql> exit

> 这种方法设置后，只是暂时的，数据库重启后，会变为原来的数值，要想永久，设置完后修改配置文件my.cnf

3、需要重启数据库

修改 my.conf 

max_connection = 1000;












