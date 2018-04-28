
#### 1、冒泡排序
    遍历依次对比一次一次将小的值移动到前边.
```php
    function bubble_sort($arr)  
    {  
        $count = count($arr);  
        if (0 == $count) {
            return false;  
        }
        for($i = 0; $i < $count; $i++){  
            for($j = 0; $j< $count-1-$i; $j++){
                if($arr[$j] > $arr[$j+1]){
                    $temp        = $arr[$j];
                    $arr[$j]     = $arr[$j+1];
                    $arr[$j+1]   = $temp;
               }
          }
        }  
        return $arr;  
    }
```

#### 2、快速排序
    递归；以数组第一个值为依据，每次都将小的值分到左边，大的分到右边，分子化操作完，大小就已经分完排好了；
    最后 = left + array[0] + right 就是排好的结果了。
```php
    function quick_sort(array $list) {
        $len = count($list);
        if ($len <= 1) {
            return $list;
        }
        $pivotValue = $list[0];
        $left = array();
        $right = array();
        for ($i = 1; $i < $len; $i++) { 
            if ($list[$i] < $pivotValue) {
                $left[] = $list[$i];
            }else{
                $right[] = $list[$i];
            }
        }
        $left = quick_sort($left);
        $right = quick_sort($right);
        return array_merge($left, array($pivotValue), $right);
    }
```

#### 3、二分查找（折半查找）
    适用于有序数组，值可对比操作的数组
```php
    function binSearch($arr, $target){  
        $height = count($arr)-1;  
        $low = 0;  
        while($low <= $height){  
            $mid = floor(($low+$height)/2);//获取中间数

            //两值相等，返回 
            if($arr[$mid] == $target){  
                return $mid; 

            //元素比目标大，查找左部 
            } elseif ($arr[$mid] < $target){
                $low = $mid + 1;  

            //元素比目标小，查找右部
            } elseif ($arr[$mid] > $target){  
                $height = $mid - 1;  
            }  
        }  
        return "查找失败";  
    }
```

#### 4、递归实现二分查找
```php
    function binaryRecursive($arr,$low,$top,$target){
        if($low<=$top){
            $mid = floor(($low+$top)/2);
            if($arr[$mid]==$target){
                return $arr[$mid];
            }elseif($arr[$mid]<$target){
                return binaryRecursive($arr,$mid+1,$top,$target);
            }else{
                return binaryRecursive($arr,$low,$top-1,$target);
            }
        }else{
            return -1;
        }
    }
```

#### 5、遍历一个文件下的所有文件和子文件夹
```php
    function my_scandir($dir){
        $files = array();
        if($handle = opendir($dir)) {
            while (($file = readdir($handle))!== false) {
                if($file != '..' && $file != '.') {
                    if(is_dir($dir."/".$file)){
                        $files[$file]=my_scandir($dir."/".$file);
                    }else{
                        $files[] = $file;
                    }
                }
            }

            closedir($handle);
            return $files;
        }
    }
```

#### 6、二维数组的排序
    PHP内置的自定义排序工具
```php
    $arr = [
        ['id'=>3, 'name'=>'name3'],
        ['id'=>5, 'name'=>'name5'],
        ['id'=>1, 'name'=>'name1'],
        ['id'=>2, 'name'=>'name2'],
        ['id'=>9, 'name'=>'name9']
    ];
    usort($arr, function($a, $b){
        return $b['id'] - $a['id'];
    });
    echo '<pre>';
    var_dump($arr);
```

#### 7、给定一个所有元素为非负的数组，将数组中的所有数字连接起来，求最大的那个数
```php
    $arr1 = [4, 94, 9, 14, 1];
    function get_max($arr) {
        usort($arr, function($a, $b){
            if ($a==$b) {
                return 0;
            }
            return (int) $a.$b < $b.$a;
        });
        return implode('', $arr);
    }
    echo get_max($arr1);
```

#### 8、有面额（1000 元、500 元、100 元、50 元、20 元、10 元、5 元、1 元）的卡，输入金额，返回组成金额的最少卡数
```php
    $fee = 54;
    function getCards($fee) {
        $feeArr = [1000, 500, 100, 50, 20, 10, 5, 1]; // 排好顺序 大->小
        $cards = 0;
        foreach ($feeArr as $c) {
            $cards += floor($fee/$c);
            $fee = $fee%$c;
        }
        return $cards;
    }
    echo getCards($fee);
```

#### 9、**********************************************************************************
    ‘1 2 3 4 5 6 7 8 9 = 110’
    为了使等式成立，需要在数字间填入加号或者减号（可以不填，但不能填入其它符号）。
    之间没有填入符号的数字组合成一个数，例如：12 + 34 + 56 + 7 - 8 + 9 就是一种合格的填法。

```php
    class Rank
    {
        public $data = array();
        public $operate = array('', '-', '+');
        public $originLen = 0;
        public $sum = 0;
        public function __construct(array $data, $sum) {
            $this->originLen = count($data);
            $this->sum = $sum;
            foreach ($data as $k => $value) {
                $this->data[$k*2] = $value;
            }
        }

        public function ternary($number) {
            $pos = 2 * $this->originLen - 3;
            do {
                $mod = $number % 3;
                $number = (int)($number / 3);
                $this->data[$pos] = $this->operate[$mod];
                $pos -= 2;
            } while($number);
            //高位补0
            while ($pos > 0) {
                $this->data[$pos] = $this->operate[0];
                $pos -= 2;
            }
            ksort($this->data);
        }

        public function run() {
            $result = array();
            $times = pow(3, $this->originLen - 1);
            for ($i = 0; $i < $times; $i++) {
                //模拟3进制的运算
                $this->ternary($i);
                //决策
                $str = implode('', $this->data);
                if (eval("return $str;") == $this->sum) {
                    $result[] = $str;
                }
            }
            return $result;
        }
    }

    //输入:[1 2 3 4 5 6 7 8 9]
    $rank = new Rank([1, 2, 3, 4, 5, 6, 7, 8, 9], 110);
    array_walk($rank->run(), function($value) {
        echo $value;
        echo "<br/>";
    });
```

#### 10、通过平面内的四个坐标点判断是否是正方形
```php
    function iszf($p) {
        // 求出4点的中心点
        $oxs = 0;
        $oys = 0;
        foreach ($p as $t) {
            $oxs = bcadd($oxs, $t[0]);
            $oys = bcadd($oys, $t[1]);
        }
        $o = [bcdiv($oxs, 4), bcdiv($oys, 4)];

        // 求出4个向量 (由中心向4个点)
        $ol = [];
        foreach ($p as $t) {
            $ol[] = [bcsub($o[0], $t[0]), bcsub($o[1], $t[1])];
        }

        // 用其中一个向量与其余向量证明平行和垂直 a,b是两个向量 a=（a1,a2） b=（b1,b2） 
        // 平行 a1b2=a2b1 or a1b1=a2b2
        // 垂直 a1b1+a2b2=0
        $oa = array_pop($ol);
        $iszf = true;
        foreach ($ol as $oli) {
            // 垂直 or 平行 (使用高精度计算函数)
            if ( (bcmul($oa[0], $oli[0])+bcmul($oa[1], $oli[1])==0) || (bcmul($oa[0], $oli[1])==bcmul($oa[1], $oli[0])) || (bcmul($oa[0], $oli[0])==bcmul($oa[1], $oli[1])) ) {
                continue;
            }
            $iszf = false;
        }
        return $iszf;
    }

    $p[] = [0,5];
    $p[] = [0,-5];
    $p[] = [-5,0];
    $p[] = [5,0];

    var_dump(iszf($p));
```







