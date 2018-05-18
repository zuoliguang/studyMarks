## Python 队列queue

### 一、队列queue

队列提供了一个安全可靠的共享数据使用方案。

队列 queue 多应用在多线程场景，多线程访问共享变量。

对于多线程而言，访问共享变量时，队列 queue 的线程安全的。

因为queue使用了一个线程锁 (pthread.Lock())，以及三个条件变量 (pthread.condition()),来保证了线程安全。

### 二、队列数据存取规则：

|   数据使用方式  |          类名          |                              作用                              |            示例            |
| :------------- |:----------------------:|:-------------------------------------------------------------:| -------------------------:|
|  FIFO先进先出   |     Queue(maxsize)     |   先进入队列的数据，先取出，maxsize:>=0，设置队列长度，0为无限长   |     q = queue.Queue()     |
|  FILO先进后出   |   LifoQueue(maxsize)   |   先进入队列的数据，最后取出,maxsize:>=0,设置队列长度，0为无限长   |   q = queue.LifoQueue()   |
| Priority优先级  | PriorityQueue(maxsize) | 设置优先标志，优先取出高标志位,maxsize:>=0,设置队列长度，0为无限长 | q = queue.PriorityQueue() |







