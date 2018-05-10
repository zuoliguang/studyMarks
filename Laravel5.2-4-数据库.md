## 4、数据库

### 4.1 原生数据库操作

Laravel提供的DB类 `use Illuminate\Support\Facades\DB` ;

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

自行创建的模型 `use App\Member`

***使用模型数据库操作***
```php
public function model()
{
    header("Content-type: text/html; charset=utf-8");

    // 1、获取全部数据
    $members = Member::all(); 

    // 2、获取条件搜索数据
    $members = Member::where('id', '<', 10)->get(); 

    // 3、分块结果
    Member::chunk(2, function($members){
        foreach ($members as $member) {
            echo $member->name;
            echo '<br/>';
        }
        echo '------------------<br/>';
    });

    // 4、取回单个信息
    $member = Member::find(1);
    $member = Member::where('id', '>', 10)->first();
    var_dump($member);

    // 5、取回指定数据集
    $members = Member::find([12, 13, 14]);
    foreach ($members as $member) {
        echo $member->name;
        echo "<br/>";
    }

    // 6、添加
    $member = new Member;
    $member->name 		= 'model';
    $member->age 		= 40;
    $member->tel 		= '88888888';
    $member->address 	= 'peking zhanghao dizhi hahah';
    $member->score 		= 99;
    $member->class 		= '2-9';
    $member->ext_info 	= 'this is a model test model info';
    $member->save();

    // 7、更新
    $member = Member::find(14);
    $member->name = 'test_mode';
    $member->save();

    // 8、批量更新
    Member::where('id', 8)->update(['name'=>'test_update']);
    Member::where('id', '>', 8)->where('id', '<=', 13)->update(['name'=>'test_update_agin']);

    // 9、删除
    $member = Member::find(7);
    $member->delete();

    // 10、批量删除
    Member::destroy(1);
    Member::destroy([1, 2, 3]);

    // 11、获取关联信息
    $member = Member::find(2);

    // 12、获取一对一的数据
    $pwd = $member->pwd; // 一对一
    var_dump($pwd->pwd);

    // 获取一对多的数据
    $says = $member->say; // 一对多
    foreach ($says as $say) {
        var_dump($say->say);
        echo '<br/>';
    }
}
```

### 4.3 分页

Laravel 的分页器与查询构造器、Eloquent ORM 集成在一起，并提供方便易用的数据结果集分页。

***查询构造器分页***

```php
// 每页显示 15 项
$users = DB::table('users')->paginate(15);
```

***简单分页***

分页视图中显示简单的「下一页」和「上一页」的链接，即不需要显示每个页码的链接

```php
$users = DB::table('users')->simplePaginate(15);
```

***Eloquent 模型分页***

```php
$users = App\User::paginate(15);
$users = User::where('votes', '>', 100)->paginate(15);
$users = User::where('votes', '>', 100)->simplePaginate(15);
```

***Blade 模板显示结果集并渲染页面链接***

```php
<div class="container">
    @foreach ($users as $user)
        {{ $user->name }}
    @endforeach
</div>

{{ $users->links() }}
```

> 注：links 方法将会链接渲染到结果集中其余的页面。每个链接都包含了正确的 page 查询字符串变量。记住，links 方法生成的 HTML 与 Bootstrap CSS 框架 兼容。

***将结果转换为 JSON***

```php
return App\User::paginate();
```

***分页器实例方法***

每个分页器实例可以通过以下方法获取额外的分页信息

* $results->count()
* $results->currentPage()
* $results->firstItem()
* $results->hasMorePages()
* $results->lastItem()
* $results->lastPage() （使用 simplePaginate 时不可用）
* $results->nextPageUrl()
* $results->perPage()
* $results->previousPageUrl()
* $results->total() （使用 simplePaginate 时不可用）
* $results->url($page)

### 4.4 初始化测试数据

### 4.5 迁移数据
