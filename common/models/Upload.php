<?php
namespace common\models;

/**
 * 上传文件类
 * 用法查看文档documents\common\models\Upload.md
 */
class Upload
{
    public $file;
    public $fileTypeAllow;
    public $fileType;
    public $uploadDir;
    public $savePath;
    public $fileName;
    public $tmpName; // 文件临时地址
    public $maxSize;
    public $size;
    public $destination; // 文件将存放的完整路径
    public $status;
    public $result;

    public function __construct()
    {
        ignore_user_abort(true);
        ini_set('upload_max_filesize', '30000M');
        ini_set('post_max_size','30000M');
        ini_set('memory_limit','30000M');
        ini_set('max_file_uploads','30000M');
        set_time_limit(0);
        $this->uploadDir = dirname(dirname(__DIR__)) . "/frontend/web/upload/";
        $this->fileTypeAllow = ['xlsx', 'xls', 'png', 'jpg', 'jpeg', 'zip', 'sql','txt','wav'];
        $this->maxSize = 1024*1024*10000;
        $this->status = false;
        $this->result = [
            'code' => 0,
            'message' => '',
        ];
    }
    /**
     * 装载文件上传配置
     *
     * @param array $config 
     * @return boolean
     * @example $config = [
     *              'file' => $_FILES['file'], // 必须
     *              'savePath' => '/var/www/upload/', // 必须,必须以/结束.如果以/开头则保存的绝对路径,如果不是/开头,则保存到/frontend/web/upload/目录下
     *              'fileName' => '', 默认使用上传文件的文件名,$_FILES['file']['name']
     *              'fileTypeAllow' => ['jpg', 'jpeg', 'png']; // 默认['xlsx' 'xls', 'png', 'jpg', 'jpeg', 'zip', 'sql'];
     *              'maxSize' => 1024*1024*2, // 单位byte,默认两兆
     *          ]
     */
    public function load($config){
        foreach ($config as $k => $v) {
            $this->$k = $v;
        }
        $this->setConfig();
        $check = $this->checkConfig();
        if (!$check) return false;
        $result = $this->checkFile();
        if (!$result) return false;
        $this->setDestination();
        return true;
    }

    public function save(){
        if (!$this->result['message'] == '') return false;
        $dir = $this->makeDir();
        $destination = iconv("UTF-8", "GBK", $this->destination);
        $tmpName = $this->tmpName;
        if (@move_uploaded_file($tmpName, $destination)) {
            $this->result['code'] = 1;
            $this->result['message'] = 'success';
            return true;
        } else {
            $this->result['message'] = 'move file failed';
            return false;
        }
    }

    public function getError(){
        if ($this->result['message'] == '') return true;
        return $this->result;
    }

    private function checkFile(){
        // 文件错误,文件大小,文件类型
        if ($this->file['error']) {
            $this->result['message'] = $this->file['error'];
            return false;
        }

        $size = $this->file['size'];
        if ($size > $this->maxSize) {
            $this->result['message'] = "file size too large , only allow {$this->maxSize}";
            return false;
        }
        if (!$this->checkType()) {
            $typeStr = implode($this->fileTypeAllow, ',');
            $this->result['message'] = "file type not allow , only allow {$typeStr}";
            return false;
        }
        return true;
    }

    private function setDestination(){
        $savePath = $this->savePath;
        $first = substr($savePath, 0, 1);
        if ('/' == $first) {
            $this->uploadDir = $this->savePath;
            $this->destination = $this->savePath . $this->fileName;
        } else {
            $this->uploadDir = $this->uploadDir . $this->savePath;
            $this->destination = $this->uploadDir . $this->fileName;
        }
    }

    private function setConfig(){
        $file = $this->file;
//        $this->fileName = $file['name'];
        if (!isset($this->fileName)) $this->fileName = $file['name'];
        $this->tmpName = $file['tmp_name'];
        $this->size = $file['size'];
    }

    private function checkType(){
        $fileName = $this->fileName;
        $this->fileType = isset(pathinfo($fileName)['extension'])?pathinfo($fileName)['extension']:'';
        if (!in_array($this->fileType, $this->fileTypeAllow)) {
            return false;
        }
        return true;
    }
    
    private function checkConfig(){
        if (!$this->savePath) {
            $this->result['message'] = '$savePath param not allow empty';
            return false;
        }
        if (!$this->file) {
            $this->result['message'] = '$file param not allow empty';
            return false;
        }
        return true;
    }

    private function makeDir(){
        $uploadDir = $this->uploadDir;
        $dir = iconv("UTF-8", "GBK", $uploadDir);
        if (!file_exists($dir)) {
            if (mkdir($dir, 0777, true)) {
                $this->result['message'] = 'create dir failed';
            }
            return $dir;
        }
        return $dir;
    }
}
