学习环境 Ubuntu14.04 

***1、go 安装***

1、可以到 [Golang中国](http://golangtc.com/download) 下载go的安装包

2、解压安装包`tar -C /usr/local -xzf <安装包>`

3、添加环境变量 `export PATH=$PATH:/usr/local/go/bin`到`/etc/profile`（全系统安装）或 `.bashrc`

4、执行`source ~/.bashrc` 更新更改

***1、设置 $GOPATH***

```
    vim /etc/profile
    
    # 以下添加到文件结尾
    export GOROOT=/usr/local/go  #设置为go安装的路径，有些安装包会自动设置默认的goroot
    
    export GOPATH=$HOME/gocode   #默认安装包的路径
    
    export PATH=$PATH:$GOROOT/bin:$GOPATH/bin
    
    # 添加完成重新加载环境即可
    source /etc/profile
```

***2、安装GO编辑器 liteide***

1、官网：http://liteide.org/cn/ 

2、发行版下载地址：https://sourceforge.net/projects/liteide/files

我这里下载安装的版本是 liteidex30.2.linux64-qt4.tar.bz2 

将安装包解压到 `/opt/` 目录下，开始安装 

编辑 `~/.bashrc` 文件 ，最后一行添加 `export PATH=/opt/liteide/bin:$PATH` ，然后重新加载脚本生效 `source ~/.bashrc`

`terminal` 中打开 `liteide` 直接打开软件开始编辑.

***2、下载添加 beego框架***

```
go get github.com/astaxie/beego
```

***3、启动框架，创建项目***
