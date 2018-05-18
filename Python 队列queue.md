## Python 队列queue

### 一、队列queue

队列提供了一个安全可靠的共享数据使用方案。

队列 queue 多应用在多线程场景，多线程访问共享变量。

对于多线程而言，访问共享变量时，队列 queue 的线程安全的。

因为queue使用了一个线程锁 (pthread.Lock())，以及三个条件变量 (pthread.condition()),来保证了线程安全。

### 二、队列数据存取规则：

| 数据使用        | 类名                   | 作用                                                           | 示例                       |
| :------------- |:----------------------:|:-------------------------------------------------------------:| --------------------------:|
| FIFO先进先出    | Queue(maxsize)         | 先进入队列的数据，先取出，maxsize:>=0，设置队列长度，0为无限长    | q = queue.Queue()          |
| FILO先进后出    | LifoQueue(maxsize)     | 先进入队列的数据，最后取出,maxsize:>=0,设置队列长度，0为无限长    | q = queue.LifoQueue()      |
| Priority优先级  | PriorityQueue(maxsize) | 设置优先标志，优先取出高标志位,maxsize:>=0,设置队列长度，0为无限长 | q = queue.PriorityQueue() |

代码：

```python
#!/usr/bin/python
# -*- coding: UTF-8 -*-
import Queue

### 一、先进先出 ###
     str = ''
     q = Queue.Queue()
     for i in range(10):
         q.put(i)
     for i in range(10):
         str += '%d => ' % q.get()
     return str 
     # 0 => 1 => 2 => 3 => 4 => 5 => 6 => 7 => 8 => 9 =>

### 二、后进先出 ###
     str = ''
     q = Queue.LifoQueue()
     for i in range(10):
         q.put(i)
     for i in range(10):
         str += '%d => ' % q.get()
     return str 
     # 9 => 8 => 7 => 6 => 5 => 4 => 3 => 2 => 1 => 0 =>

### 三：按优先标志位读取 ###
     str = ''
     p = Queue.PriorityQueue()
     p.put((3,"3"))
     p.put((1,"1"))
     p.put((4,"4"))
     p.put((2,"2"))
     size = p.qsize()
     for i in range(size):
         str += '%s => ' % p.get()[1]
     return str 
    # 1 => 2 => 3 => 4 =>
    # 排列顺序 
    # (1, '1')
    # (2, '2')
    # (3, '3')
    # (4, '4')
    
### 四：多元组判断 ###
     str = ''
     p = Queue.PriorityQueue()
     p.put((1, 4, "a"))
     p.put((2, 1, "666"))
     p.put((1, 3, "4"))
     p.put((2, 2, "2"))
     p.put((1, 9, "9"))
     size = p.qsize()
     for i in range(size):
         str += '%s => ' % p.get()[2]
     return str 
    # 4 => a => 9 => 666 => 2 =>
    # 排列顺序 
    # (1, 3, "4")
    # (1, 4, "a")
    # (1, 9, "9")
    # (2, 1, "666")
    # (2, 2, "2")
```

### 三、队列的常用方法和属性

> Queue.qsize() 返回队列的大小

> Queue.empty() 如果队列为空，返回True,反之False

> Queue.full() 如果队列满了，返回True,反之False

> Queue.full 与 maxsize 大小对应

> Queue.get([block[, timeout]])获取队列，timeout等待时间

> Queue.get_nowait() 相当Queue.get(False)

> 非阻塞 Queue.put(item) 写入队列，timeout等待时间

> Queue.put_nowait(item) 相当Queue.put(item, False)

> Queue.task_done() 在完成一项工作之后，Queue.task_done()函数向任务已经完成的队列发送一个信号

> Queue.join() 实际上意味着等到队列为空，再执行别的操作

### 四、队列数据进出规则实例 ：

也是一个最简单的生产者消费者例子。

```python
# -*- coding: UTF-8 -*- 
import Queue, time, threading, random

l = []
q = Queue.LifoQueue() 

def productor(name, s):
    time.sleep(s)
    print('服务员{}有时间了'.format(name))
    q.put(name)

def customer():
    s = q.get()
    print('服务员{}被叫走了'.format(s))

for i in range(5):
    n = random.randint(1, 7)
    t = threading.Thread(target=productor, args=(i, n))
    l.append(t)
    t.start()

for i in l:
    i.join()
    customer()
```



