学习环境 Ubuntu14.04 

***0、go 安装***

1、可以到 (Golang中国)[http://golangtc.com/download] 下载go的安装包

2、解压安装包tar -C /usr/local -xzf <安装包>

3、添加环境变量`export PATH=$PATH:/usr/local/go/bin到/etc/profile（全系统安装）或 .bashrc(bash中)或者 .zshrc(zsh中)

4、执行source .zshrc更新更改

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

***2、下载添加 beego框架***

```
go get github.com/beego/bee
```

***3、启动框架，创建项目***
