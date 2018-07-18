
#### 以下为个人工作时碰到自认为比较好的用法SQL语句，不包含之前工作经验中碰到的经典

***1、更新查询关联到另一张表内stock多条数据之和***

##### 商品的后台管理 自动检测商品库存并更新主商品在架的状态

###### 上架
```php
$sql = "UPDATE `ec_product` p SET `status`=1 WHERE EXISTS 
	(SELECT 1 FROM `ec_product_item` WHERE p.id = `product_id` GROUP BY `product_id` HAVING SUM(stock)>0) AND `status`=2";
```

###### 下架
```php
$sql = "UPDATE `ec_product` p SET `status`=2 WHERE EXISTS 
	(SELECT 1 FROM `ec_product_item` WHERE p.id = `product_id` GROUP BY `product_id` HAVING SUM(stock)=0) AND `status`=1";
```

***2、批量修改字段 SQL***

> 当 title = title1 时，name => name1, date => date1;

> 当 title = title2 时, name => name2, date => date2;

```php
$sql = "UPDATE `table_name` SET 
	# 批量更新 name 字段
	`name` = CASE
	WHEN `title` = 'title1' THEN 'name1'
	WHEN `title` = 'title2' THEN 'name2'
	ELSE `name` END,
	# 批量更新 date 字段
	`date` = CASE
	WHEN `title` = 'title1' THEN 'date1'
	WHEN `title` = 'title2' THEN 'date2'
	ELSE `date` END
	# 针对以下条件的数据修正
	WHERE `title` IN ('title1','title2')";
```

    该方式的操作可以用来自己构造批量修改的SQL语句，比遍历逐句修改节省且更有效。

***3、判断获取的结果，直接输出想要的状态信息，避免二次处理***

> online_state 字段不同代表不同的含义，下面以此方式直接输出

```mysql
SELECT `spu_code`, 
CASE 
WHEN `online_state` = 0 THEN '待入库' 
WHEN `online_state` = 1 THEN '已入库' 
WHEN `online_state` = 2 THEN '待上架' 
WHEN `online_state` = 3 THEN '已上架' 
WHEN `online_state` = 4 THEN '已下架' 
END AS `online_state` 
FROM `pro_product`;
```

***4、SQL 抽取随机10条数据***

> order by rand()

```mysql
select * from log order by rand() LIMIT 0,10
```

***5、MySQL链表更新数据***

> 将 tab2 表的 name 更新到 tab1 表的 nick_name ，条件 tab2.out_id = tab1.id；

```mysql
-- SQL（1）
UPDATE tab1 (LEFT) JOIN tab2 ON tab1.id = tab2.id SET tab1.nick_name = tab2.name WHERE tab2.out_id = tab1.id;
-- SQL（2）
UPDATE tab1, tab2 SET tab1.nick_name = tab2.name WHERE tab1.id = tab2.id AND tab2.out_id = tab1.id;
```

***6、去除联合字段重复数据，并添加联合唯一索引***

> xy_sku_image 表 处理 sku_code，tm_url 字段
```mysql
# 查出联合字段重复的数据
SELECT * FROM (SELECT *, CONCAT(clum01, clum02) AS uniq_index FROM `table_name`) t WHERE t.uniq_index IN (
SELECT uniq_index FROM (SELECT CONCAT(clum01, clum02) AS uniq_index FROM `table_name`) tt GROUP BY uniq_index HAVING COUNT(uniq_index) > 1
);

# 保留id最大的数据
DELETE FROM `table_name` WHERE id NOT IN (
	SELECT maxid FROM ( SELECT MAX(id) AS maxid, CONCAT(clum01, clum02) AS uniq_index FROM `table_name` GROUP BY uniq_index ) t
)

# 添加唯一索引的方法
ALTER TABLE `table_name` ADD UNIQUE INDEX uniq_index(clum01, clum02); 
```

* 注意：当clum01, clum02 字段为 NULL 时，会发现搜出的数据有问题
* 解决方案：将有可能为 `NULL` 的字段用 `IFNULL(clum01, 'default')` 判断，给出默认值即可。

***7、数据库引入外部源SQL文件***

> 数据库加载外部SQL文件

```
//mysql -u账号 -p密码 -D数据库名 < sql文件绝对路径

mysql -uroot -p123456 -Dppgo_job</home/wwwroot/ppgo_job/ppgo_job.sql
```
***8、mysql 条件 if 、case

> 数据库的表达式用法

if 表达式 `IF(expr1, expr2, expr3)` 

> 如果 expr1 是TRUE，则 IF()的返回值为expr2; 否则返回值则为 expr3。
```mysql
select id, name, if(sex=1, "男", "女") as sex from user;
```
case 查看 [`3`] 标题内容

ifnull 表达式 `IFNULL(expr1, expr2)`

> 假如expr1 不为 NULL，则 IFNULL() 的返回值为 expr1; 否则其返回值为 expr2。
```mysql
SELECT id, IFNULL(img_sku_code, sku_code) AS sku_code FROM `image_sku`;
```

