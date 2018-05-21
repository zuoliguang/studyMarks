> 本文参考了https://www.cnblogs.com/syqlp/p/5462459.html，诚挚感谢

试验目标：用python27实现文字识别OCR功能

环境：win10(64位)

     Python2.7.9

实现流程：

1． 安装pyocr

到https://pypi.python.org/pypi/pyocr/0.4.1下载pyocr-0.4.1.tar.gz

解压后，进入pyocr-0.4.1.tar.gz目录里执行下面的命令进行安装：

Python setup.py install

2． 安装PIL

针对win64位的PIL，到下面的网址下载 Pillow-2.1.0.win-amd64-py2.7.exe (md5) ：   https://pypi.python.org/packages/2.7/P/Pillow/Pillow-2.1.0.win-amd64-py2.7.exe#md5=3abe747fbbcdba151e48255b96639b69

 下载后选默认直接安装即可。

如果是32位系统，到http://www.pythonware.com/products/pil/index.htm下载安装

3． 安装tesseract-ocr

到下面网址下载：

http://jaist.dl.sourceforge.net/project/tesseract-ocr-alt/tesseract-ocr-setup-3.02.02.exe

下载后选默认直接安装。

4． 测试

测试代码如下：
```python
import sys
import os

os.environ['NLS_LANG'] = 'SIMPLIFIED CHINESE_CHINA.UTF8'

try:

   from pyocr import pyocr
   from PIL import Image

except ImportError:

   print '模块导入错误,请使用pip安装,pytesseract依赖以下库：'
   print 'http://www.lfd.uci.edu/~gohlke/pythonlibs/#pil'
   print 'http://code.google.com/p/tesseract-ocr/'
   raiseSystemExit

tools = pyocr.get_available_tools()[:]
if len(tools) == 0:
   print("No OCR tool found")
   sys.exit(1)

print("Using '%s'" %(tools[0].get_name()))
print tools[0].image_to_string(Image.open('D:\\123.png'),lang='eng')
print tools[0].image_to_string(Image.open('D:\\3434.png'),lang='chi_sim')
#printtools[0].image_to_string(Image.open('D:\\3535.png'),lang='chi_sim')

```
可查看运行结果，英文好使，中文还需添加支持插件。
