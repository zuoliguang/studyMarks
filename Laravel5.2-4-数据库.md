## 4、数据库

Laravel提供的DB类 `use Illuminate\Support\Facades\DB` ;

### 4.1 原生数据库操作

代码实例

***新增***
```php
public function db_add_user()
{
    // 1、单处理
    $member = ['name'=>'zlhppppp', 'age'=>45, 'tel'=>'9999999999', 'address'=>'testkkkkkkkk', 'score'=>90, 'class'=>'3-5', 'ext_info'=>'hahahahaha'];
    DB::table('member')->insert($member); // 插入数据不返回id
    $id = DB::table('member')->insertGetId($member); // 插入数据返回id
    var_dump($id);

    // 2、批处理
    $members = [
    ['name'=>'zlhxxxxx', 'age'=>45, 'tel'=>'9999999999', 'address'=>'testkkkkkkkk', 'score'=>90, 'class'=>'3-5', 'ext_info'=>'hahahahaha'],
    ['name'=>'zlhzzzzz', 'age'=>45, 'tel'=>'9999999999', 'address'=>'testkkkkkkkk', 'score'=>90, 'class'=>'3-5', 'ext_info'=>'hahahahaha']
    ];
    DB::table('member')->insert($members);
    echo "批量插入数据";

    // 3、sql操作 
    DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);
}
```

***更新***
```php
public function db_update_user()
{
    // 1、依据id更新
    $data = ['name'=>'zuoliguang'];
    DB::table('member')->where('id', 4)->update($data);

    // 2、自增减
    DB::table('member')->where('id', 4)->increment('score'); // 自增
    DB::table('member')->where('id', 4)->decrement('score'); // 自减

    // 3、sql 操作
    $affected_rows = DB::update('update users set votes = 100 where name = ?', ['John']);

    // 4、数据库事务
    // -----a、架构封装的 ( 第二参数定义在发生死锁时应该重新尝试事务的次数 )
    DB::transaction(function () {
        DB::table('users')->update(['votes' => 1]);
        DB::table('posts')->delete();
    }, 5);
    // -----b、手动
    DB::beginTransaction(); // 开启事务
    //业务代码TODO
    if (false) {
        DB::rollBack(); // 回滚事务
    } else {
        DB::commit(); // 提交事务
    }
}
```

***查询***
```php
public function db_get_user()
{
    // 1、条件查询
    $data = DB::select('select * from member where id = ?', [1]); // 使用sql查询
    $data = DB::table('member')->get(); // 获取
    $data = DB::table('member')->offset(2)->limit(5)->get(); // 分页获取
    $data = DB::table('member')->inRandomOrder->get(); // 获取并混排
    $data = DB::table('member')->where('name', 'aaa')->get(); // 条件搜索
    $data = DB::table('member')->where('name', 'aaa')->first(); // 条件搜索的第一条
    $data = DB::table('member')->where('id', '>', '0')->value('id'); // 条件搜索的第一条的id字段
    $data = DB::table('member')->pluck('name', 'id'); // 获取表 name 信息 并以 id为键，name为值
    $data = DB::table('member')->select('id', 'name')->addSelect('age')->get(); // 字段

    $data = DB::table('member')->where('name', 'like', '%a%')->get(); // 模糊条件搜索
    $data = DB::table('member')->where('score', '>=', 70)->get(); // 判断条件搜索

    $data = DB::table('member')->whereBetween('score', [70, 90])->get(); // 范围条件搜索
    $data = DB::table('member')->whereNotBetween('score', [70, 90])->get(); // 范围条件搜索

    $data = DB::table('member')->whereIn('class', ['3-1', '3-2'])->get(); // 枚举条件搜索
    $data = DB::table('member')->whereNotIn('class', ['3-1', '3-2'])->get(); // 枚举条件搜索

    $data = DB::table('member')->where([ ['score', '>=', 70], ['class', '=', '3-2'] ])->get(); // 组合条件搜索
    // var_dump($data);die();

    // 2、结果分块
    DB::table('member')->orderBy('id', 'desc')->chunk(2, function($members){
        foreach ($members as $member) {
            // 该方式用来阻止循环
            if ($member->id <= 1 ) {
                return false;
            }
            // var_dump($member);
        }
    });

    // 3、聚合函数
    $data = DB::table('member')->count(); // 数量
    $data = DB::table('member')->max('age'); // 最大值
    $data = DB::table('member')->avg('age'); // 平均值
    $data = DB::table('member')->distinct()->pluck('age'); // 去重

    // 4、链式查询
    $data = DB::table('member')
            ->select('class', DB::raw(' SUM(score) as total_score '))
            ->groupBy('class')
            ->havingRaw(' SUM(score) > 60 ')
            ->get();
    // var_dump($data);

    // 5、连表查询
    $data = DB::table('member')
            // ->join('m_pwd', 'member.id', '=', 'm_pwd.m_id')
            ->leftJoin('m_pwd', 'member.id', '=', 'm_pwd.m_id')
            ->select('member.name', 'm_pwd.pwd')->get();
    // var_dump($data);

    // 6、Where Exists 语句
    $data = DB::table('member')
        ->whereExists(function($query){
            $query->select(DB::raw(1))
                ->from('m_pwd')
                ->whereRaw('m_pwd.m_id=member.id');
        })->get();
        // sql : select * from member where exists ( select 1 from m_pwd where m_pwd.m_id = member.id )
        // 即找到 m_pwd 中有对应的 member 数据
    var_dump($data);
}
```

***删除***
```php
public function db_del_user()
{
    DB::table('member')->where('id', 4)->delete();
    $deleted_rows = DB::delete('delete from users'); // sql 操作
}
```

### 4.2 Eloquent ORM 模型

### 4.3 查询构造器

### 4.4 分页

### 4.5 初始化测试数据

### 4.6 迁移数据
