注意：环境说明：CentOS6.5、root权限、原python版本 2.6.6

#### 查看python版本：
```
python --version
```

***1.下载Python-2.7.12***
```
wget https://www.python.org/ftp/python/2.7.12/Python-2.7.12.tgz
```

***2.解压***
```
tar   zxvf   Python-2.7.12.tgz
```

***3.更改工作目录***
```
cd Python-2.7.12  
```

***4.安装***
```
./configure  
make all             
make install  
make clean  
make distclean  
```

***5.查看版本信息***
```
/usr/local/bin/python2.7 -V  
```


***6.建立软连接，使系统默认的 python指向 python2.7***
```
#mv /usr/bin/python /usr/bin/python2.6.6  
#ln -s /usr/local/bin/python2.7 /usr/bin/python  
```

***7.重新检验Python 版本***
```
#python -V  
```

***8 解决系统 Python 软链接指向 Python2.7 版本后，因为yum是不兼容 Python 2.7的，所以yum不能正常工作，需要指定 yum 的Python版本***
```
#vi /usr/bin/yum  
将文件头部的
#!/usr/bin/python
改成
#!/usr/bin/python2.6.6
```

***9、pip最新安装***

pip是python的安装工具，很多python的常用工具，都可以通过pip进行安装。

要安装pip，首先要安装setuptools。下面的链接可以得到相关信息，最新版本是21.0.0:

`https://pypi.python.org/pypi/setuptools`

下载并进行安装：

```
tar vxf setuptools-21.0.0.tar.gz 
cd setuptools-21.0.0
python setup.py  install
```

安装完成后，下载pip。其信息在如下网站：

`https://pypi.python.org/pypi/pip`

下载并进行安装：

```
tar vxf pip-8.1.1.tar.gz 
cd pip-8.1.1
python setup.py install
```

安装完成后，运行pip



