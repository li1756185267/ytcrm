<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9 0009
 * Time: 15:22
 */
namespace common\models;

class PHPTxt
{
    private $path=null;

    public static function isMobile( $text ) {
//        $search = "/^1[34578]{1}\d{9}$/";
        $search = "/^\d{6,15}$/";
        if (preg_match( $search,$text) ) {
            return true;
        } else {
            return false ;
        }
    }

    public function setPath($path){

        $path = str_replace('\\','/',$path);
        $this->path = $path;
        $this->path = iconv("UTF-8", "GBK", $this->path);

    }
    public function getDataArr(){
        $strdata=file_get_contents($this->path);
        $strdata=str_replace(["\n","\n\r","|"], [",",",",","], $strdata);
        $arrdata=explode(",",$strdata);
        $res=[];
        foreach ($arrdata as $k=>$arrdatum) {
            $res[] = trim($arrdatum);
        }
        if(is_array($res)){
            return $res;
        }
        return ;
    }

    /**
     * 导入Asr群呼新用户(txt只插入号码)
     * @param	$proClassId  	(int)		项目分类Id
     * @param	$projectid  	(int)		项目Id
     * @param	$batch_id   	(int)		批次Id
     * @param	$dataArr		(array)		客户详细信息
     * @param	$phone    手机号
     * @param	$fieldNameStr   (string)    字段名
     * @param	$to_companyid	(int)		项目归属公司Id
     * @return  $result			(int)		1成功 2失败 3重复数据
     * */
    static	function addAsrCallData($batch_id,$dataArr,$phone,$fieldNameStr,$to_companyid)
    {
        global $db;

        #客户号码不可为空
        if(!is_numeric($phone)) {return  2;}
        if(strlen($phone)<5){return  2;}

        $base_sql="insert into asrcall_data_share($fieldNameStr,classif_id,batch_id,company_id,update_time,create_time)";

        $base_sql .="values($phone,0,$batch_id,$to_companyid,now(),now());";

        $r=$db->query($base_sql);
        $custinfo_id=$db->insert_id();
        if(!$r){
            return false;
        }
        #数据插入失败
        if($custinfo_id==0)return 2;

        return true;
    }
}