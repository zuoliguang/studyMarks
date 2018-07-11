设置概率算法的配置信息

设置5项奖励机制，将奖励的配置信息由概率大到概率小排列；

```php
// 转盘算法
// 奖励计算基数 'pro'
$three = [
	'1'   => [
        'id'    => 1,
        'pro'   => 50000, 
        'bag'   => '五等奖'
    ], '2'   => [
        'id'    => 2,
        'pro'   => 4000,
        'bag'   => '四等奖'
    ], '3'   => [
        'id' 	=> 3,
        'pro' 	=> 300,
        'bag'  	=> '三等奖'
    ], '4'   => [
        'id'    => 4,
        'pro'   => 20,
        'bag'  	=> '二等奖'
    ], '5'   => [
        'id'    => 5,
        'pro'   => 1,
        'bag'  	=> '一等奖'
    ]
];
```

设计思路：

```php
/**
 * 转盘概率算法
 * 每过滤一项奖的基数都会相应减去，保证最后一定是要中到我们设置的奖项上面的。
 * @param array
 */
function getRand($three)
{
	$proSum = 0;
    array_walk($three, function($vo,$k)use(&$proSum){
    	$proSum += $vo['pro'];
    });
    // $proSum 计算出奖励总基数
    foreach ($three as $key => $pro) {
    	// 随机获取范围内的整数值
        $randNum = mt_rand(1, $proSum); 
        // 如果获取的随机值在概率数范围内，中奖
        if (($randNum) <= $pro['pro']) { 
            $result = $key;
            echo "中奖概率".floatval($pro['pro']/$proSum).'<br/>';
            break;
        } else {
        	// 没有中奖的时候减出该项奖的基数
            $proSum -= $pro['pro'];
        }
    } 
    unset($three);
    return $result;
}
```




