
#### 以下为个人工作时碰到自认为比较好的用法SQL语句，不包含之前工作经验中碰到的经典

***1、更新查询关联到另一张表内stock多条数据之和***

##### 商品的后台管理 自动检测商品库存并更新主商品在架的状态

###### 上架
```
	$sql = "UPDATE `ec_product` p SET `status`=1 WHERE EXISTS 
		(SELECT 1 FROM `ec_product_item` WHERE p.id = `product_id` GROUP BY `product_id` HAVING SUM(stock)>0) AND `status`=2";
```

###### 下架
```
	$sql = "UPDATE `ec_product` p SET `status`=2 WHERE EXISTS 
		(SELECT 1 FROM `ec_product_item` WHERE p.id = `product_id` GROUP BY `product_id` HAVING SUM(stock)=0) AND `status`=1";
```

***2、批量修改字段 SQL***

* 当 title = title1 时，name => name1, date => date1;
* 当 title = title2 时, name => name2, date => date2;

```
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

```
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

```
	select * from log order by rand() LIMIT 0,10
```





