#### 用python27实现文字识别OCR功能

环境：
* win10(64位)
* Python2.7.14

实现流程：

1． 安装pyocr

到 `https://pypi.org/project/pyocr` 查看 本地安装  `pip install pyocr`

2． 安装PIL

`pip install pillow`

或者 `https://pypi.org/project/image` 安装 `pip install image`

3． 安装 `tesseract-ocr`

下载安装文件 `http://jaist.dl.sourceforge.net/project/tesseract-ocr-alt/tesseract-ocr-setup-3.02.02.exe` 

下载后直接安装，建议默认安装过程中的选项，安装目录默认 `C:\Program Files (x86)\Tesseract-OCR`

并将启动文件夹添加到 环境变量 

或者使用 `pip` 安装并手动配置 

查看 `https://pypi.org/project/tesseract-ocr` 安装 `pip install tesseract-ocr`

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
   raise SystemExit

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

网上找到的解决方法如下：

1.下载`tesseract-ocr`的中文库，地址：`https://codeload.github.com/tesseract-ocr/tessdata/zip/master`，

里面包含`tesseract`所有的文字库，大约1.1G，`chi_sim.traineddata` 为简体中文库，

将该文件放至`C:\ProgramFiles (x86)\Tesseract-OCR\tessdata`目录下。

2.控制台切换至`C:\ProgramFiles (x86)\Tesseract-OCR\tessdata`，使用命令行执行：

`combine_tessdata -e chi_sim.traineddatachi_sim.config`

执行完后，在目录下出现`chi_sim.config`的文件，打开该文件；

在`allow_blob_division        F`这一行的前面加#，注释掉

即：`#allow_blob_division        F `   

然后，在执行命令行：

`combine_tessdata -o chi_sim.traineddata chi_sim.config`

到此在使用 `chi_sim.traineddata`文件就不会报`read_params_file: parameternot found: allow_blob_division`

当然，要使用上面的命令行，需要安装`Tesseract-OCR` 

以上步骤摘自`http://www.cnblogs.com/syqlp/p/5460971.html`，不能正确执行。不知是不是系统版本的原因。

至此，文字识别，已实现了英文和数字的正确识别。
