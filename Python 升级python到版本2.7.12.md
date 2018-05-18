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
