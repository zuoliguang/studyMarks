学习环境 Ubuntu14.04 

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
