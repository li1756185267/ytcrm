# 上传文件类

## 示例

```php
    use common\models\Upload;

    class Demo
    {
        public function init(){
            $upload = new Upload();
            $config = [
                'file' => $_FILES['file'],
                'savePath' => 'images/'
            ];
            /**
            * 装载文件上传配置
            *
            * @param array $config
            * @return boolean
            * @example $config = [
            *              'file' => $_FILES['file'], // 必须
            *              'savePath' => '/var/www/upload/', // 必须,必须以/结束.  如果以/开头则保存的绝对路径,如果不是/开头,则保存到/frontend/web/upload/目录下
            *              'fileName' => '', 默认使用上传文件的文件名,$_FILES['file']['name']
            *              'fileTypeAllow' => ['jpg', 'jpeg', 'png']; // 默认['xlsx' 'xls', 'png', 'jpg', 'jpeg', 'zip', 'sql'];
            *              'maxSize' => 1024*1024*2, // 单位byte,默认两兆
            *          ]
            */
            $upload->load($config); // 成功返回true,失败返回false
            $upload->save(); // 成功返回true,失败返回false
            var_dump($upload->getError()); // load后失败返回错误信息,成功返回true, save后成功返回success,失败返回错误信息

            // 获得文件上传参数,在调用load方法后即可使用
            // $upload->file; // 将上传文件的信息
            // $upload->fileTypeAllow; // 允许的文件类型
            // $upload->fileType; // 文件类型,后缀名
            // $upload->uploadDir; // 默认保存的路径
            // $upload->savePath; // 调用者给的保存路径
            // $upload->fileName; // 文件名
            // $upload->destination; // 文件保存的完整路径名加这个文件名
            // $upload->size; // 文件大小
            // $upload->result; // 获得上传结果
        }
    }
```
