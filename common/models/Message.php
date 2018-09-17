<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 15:26
 */
namespace common\models;

use Yii;

class Message
{
    /**
     * @param int $statusCode  状态码 1 正确 ，0 错误
     * @param string $Message   状态描述
     * @param mixed $Infomation  返回的数据，混合类型
     */
    final public static function jsonReturn($statusCode = 1, $Message = '', $Infomation = '')
    {
        $data = [
            'statusCode' => $statusCode,
            'message' => $Message,
            'info' => $Infomation
        ];
        $response = Yii::$app->response;
        $response->headers->add('Content-Type', 'application/json; charset=utf-8');
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;

    }

    /**
     * 返回json,并中断代码执行
     * @param $statusCode
     * @param $Message
     * @param array $data
     */
    final public static function jsonEncode($statusCode,$Message,$data=array()){
        $res = array();
        $res['statusCode'] = $statusCode;
        $res['message'] = $Message;
        foreach ($data as $k=>$v){
            $res[$k] = $v;
        }
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
    }


}
